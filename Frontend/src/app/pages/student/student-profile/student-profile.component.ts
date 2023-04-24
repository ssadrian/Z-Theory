import { Component, OnInit } from '@angular/core';
import { CredentialService } from '../../../services/credential.service';
import { IStudent } from '../../../../models/student.model';
import { StudentService } from 'src/app/services/repository/student.service';
import {
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
} from '@angular/forms';
import { Base64Service } from 'src/app/services/base64.service';
import { IUpdateStudent } from 'src/models/update/update-student';
import { IRanking } from '../../../../models/ranking.model';
import { RankingService } from '../../../services/repository/ranking.service';
import { ICreateStudentAssignation } from '../../../../models/create/create-student-assignation';
import { MessageService } from 'primeng/api';
import { IUpdatePassword } from '../../../../models/update/update-password';
import { catchError, throwError } from 'rxjs';
import { HttpErrorResponse } from '@angular/common/http';
import { EvaluateService } from 'src/app/services/repository/evaluate.service';
import { ICreateEvaluation } from 'src/models/create/create-evaluation';
import { Skill } from 'src/models/misc/skill';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss'],
})
export class StudentProfileComponent implements OnInit {
  constructor(
    private credentials: CredentialService,
    private studentService: StudentService,
    private rankingService: RankingService,
    private fb: FormBuilder,
    private messageService: MessageService,
    private b64: Base64Service,
    private evaluateService: EvaluateService
  ) { }

  showImageUpload: boolean = false;

  isRankingsTableLoading: boolean = true;

  showPasswordChangeDialog: boolean = false;
  isPentabilitiesDialogVisible: boolean = false;

  sidebarVisible = false;

  codeForm = this.fb.group({
    code: ['', [Validators.required]],
  });

  passwordForm = this.fb.group({
    password: ['', [Validators.required]],
    new_password: ['', [Validators.required]],
  });

  student: IStudent = this.credentials.currentUser as IStudent;
  rankings: IRanking[] = [];

  subject!: number;

  form = this.fb.group({
    nickname: ['', [Validators.required]],
    name: ['', [Validators.required]],
    surnames: ['', [Validators.required]],
    password: ['', [Validators.required]],
    password_confirmation: ['', [Validators.required]],
    email: ['', [Validators.required, Validators.email]],
    tos: [false, [Validators.requiredTrue]],
    birth_date: [''],
    center: [''],
  });

  formEvaluateStudent = new FormGroup({
    responsibility: new FormControl(0),
    cooperation: new FormControl(0),
    autonomyInitiative: new FormControl(0),
    emotionalManagment: new FormControl(0),
    thinkingSkills: new FormControl(0),
  });

  #b64Avatar: string = '';

  inventory: Object[] = [
    { qty: 1, name: 'Espada' },
    { qty: 3, name: 'Diamantes' },
    { qty: 1, name: 'Mapa' },
  ];

  ngOnInit(): void {
    this.#updateRanks();
  }

  encodeAvatar(event: any): void {
    this.b64.toBase64(event).then((b64: string): void => {
      this.#b64Avatar = b64;
      this.updateAvatar();
    });

    this.toggle();
  }

  updateAvatar(): void {
    let student: IUpdateStudent = {
      avatar: this.#b64Avatar,
      name: this.student.name!,
      surnames: this.student.surnames!,
      nickname: this.student.nickname!,
      birth_date: this.student.birth_date.toString()!,
    };

    this.studentService
      .update(this.student.id, student)
      .subscribe((): void => { });
    this.student.avatar = this.#b64Avatar;

    this.showImageUpload = false;
  }

  toggle(): void {
    this.showImageUpload = true;
  }

  joinRanking(): void {
    const code: string = this.codeForm.value.code ?? '';

    // if (!this.#isValidUuid(code)) {
    //   this.messageService.add({
    //     key: 'toasts',
    //     severity: 'error',
    //     detail: `El UUID no es valido.`
    //   });
    //   return;
    // }

    const entity: ICreateStudentAssignation = {
      code: code,
      student_id: this.student.id,
    };

    this.rankingService.assignStudent(entity)
      .subscribe((): void => {
        this.#updateRanks();
        this.codeForm.reset();

        this.messageService.add({
          key: 'toasts',
          severity: 'success',
          detail: 'Petición registrada.'
        });
      });
    this.rankingService.assignStudent(entity).subscribe((): void => {
      this.#updateRanks();
      this.codeForm.reset();
    });
  }

  #isValidUuid(uuid: string): boolean {
    const uuidRegex: RegExp =
      /^[0-9A-Za-z]{8}-[0-9A-Za-z]{4}-4[0-9A-Za-z]{3}-[89ABab][0-9A-Za-z]{3}-[0-9A-Za-z]{12}$/g;
    return uuidRegex.test(uuid);
  }

  #updateRanks(): void {
    this.isRankingsTableLoading = true;

    this.rankingService
      .leaderboardsForStudent(this.student.id)
      .subscribe((rankings: IRanking[]): void => {
        this.rankings = rankings;

        this.rankings.forEach((rank: IRanking): void => {
          rank.students.sort((a: IStudent, b: IStudent) => {
            return (
              b.pivot.points - a.pivot.points ||
              a.nickname.localeCompare(b.nickname)
            );
          });
        });

        this.isRankingsTableLoading = false;
      });
  }

  showPasswordChangeForm(): void {
    this.showPasswordChangeDialog = true;
  }

  changePassword(): void {
    const formValues = this.passwordForm.value;
    const entity: IUpdatePassword = {
      id: this.credentials.currentUser?.id!,
      password: formValues.password!,
      new_password: formValues.new_password!,
    };

    this.studentService
      .updatePassword(entity)
      .pipe(
        catchError((err: HttpErrorResponse) => {
          if (!err.ok) {
            this.messageService.add({
              key: 'toasts',
              severity: 'error',
              summary: 'No se pudo cambiar la contraseña',
            });
          }

          return throwError(() => new Error('Ignore the error'));
        })
      )
      .subscribe((): void => {
        this.passwordForm.reset();

        this.messageService.add({
          key: 'toasts',
          severity: 'success',
          summary: 'Contraseña cambiada!',
        });
        this.onShowPasswordDialogReject();
      });
  }

  onShowPasswordDialogReject(): void {
    this.showPasswordChangeDialog = false;
  }

  showDialog() {
    this.isPentabilitiesDialogVisible = true;
  }

  sumFields() {
    const {
      responsibility,
      cooperation,
      autonomyInitiative,
      emotionalManagment,
      thinkingSkills,
    } = this.formEvaluateStudent.value;

    const total =
      responsibility! +
      cooperation! +
      autonomyInitiative! +
      emotionalManagment! +
      thinkingSkills!;

    if (total > 1000) {
      this.messageService.add({
        key: 'toasts',
        severity: 'error',
        summary: 'La suma de todos los campos no puede dar más de 1000',
      });

      return;
    }

    let evaluation: ICreateEvaluation = {
      evaluator: this.student.id,
      subject: this.subject,
      skill: Skill.Responsibility,
      kudos: responsibility!
    };

    this.evaluateStudent(evaluation);

    evaluation.skill = Skill.Responsibility;
    evaluation.kudos = responsibility!;

    this.evaluateStudent(evaluation);

    evaluation.skill = Skill.Autonomy;
    evaluation.kudos = autonomyInitiative!;

    this.evaluateStudent(evaluation);

    evaluation.skill = Skill.Cooperation;
    evaluation.kudos = cooperation!;

    this.evaluateStudent(evaluation);

    evaluation.skill = Skill.Emotional;
    evaluation.kudos = emotionalManagment!;

    this.evaluateStudent(evaluation);

    evaluation.skill = Skill.Thinking;
    evaluation.kudos = thinkingSkills!;

    this.evaluateStudent(evaluation);
  }

  evaluateStudent(entity: ICreateEvaluation) {
    this.evaluateService.create(entity).subscribe();
  }

  showEvaluationSideBarForStudent(studentId: number) {
    if (studentId === this.student.id) {
      return;
    }
    this.subject = studentId;
    this.sidebarVisible = true;
  }
}
