import {Component, OnInit} from '@angular/core';
import {CredentialService} from '../../../services/credential.service';
import {IStudent} from '../../../../models/student.model';
import {StudentService} from 'src/app/services/repository/student.service';
import {FormBuilder, Validators} from '@angular/forms';
import {Base64Service} from 'src/app/services/base64.service';
import {IUpdateStudent} from 'src/models/update/update-student';
import {IRanking} from '../../../../models/ranking.model';
import {RankingService} from '../../../services/repository/ranking.service';
import {ICreateStudentAssignation} from '../../../../models/create/create-student-assignation';
import {MessageService} from 'primeng/api';
import {IUpdatePassword} from '../../../../models/update/update-password';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss'],
})
export class StudentProfileComponent implements OnInit{
  constructor(
    private credentials: CredentialService,
    private studentService: StudentService,
    private rankingService: RankingService,
    private fb: FormBuilder,
    private messageService: MessageService,
    private b64: Base64Service
  ) {
  }

  show: boolean = false;

  loading: boolean = true;

  codeForm = this.fb.group({
    code: ['', [Validators.required]],
  });

  passwordForm = this.fb.group({
    password: ['', [Validators.required]],
    new_password: ['', [Validators.required]],
  });

  student: IStudent = this.credentials.currentUser as IStudent;
  rankings: IRanking[] = [];

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

  #b64Avatar: string = '';

  inventory: Object[] = [
    {qty: 1, name: 'Espada'},
    {qty: 3, name: 'Diamantes'},
    {qty: 1, name: 'Mapa'},
  ];

  ngOnInit(): void {
    this.#updateRanks();
  }

  showPasswordChangeForm(): void {
    console.log('Test');
    this.messageService.add({
      key: 'passwordChange',
      sticky: true,
      severity: 'info',
      summary: 'Cambiar Contraseña'
    });
  }

  changePassword(): void {
    const formValues = this.passwordForm.value;
    const entity: IUpdatePassword = {
      id: this.credentials.currentUser?.id!,
      password: formValues.password!,
      new_password: formValues.new_password!,
    };

    this.studentService.updatePassword(entity)
      .subscribe(response => {
        this.messageService.clear('passwordChange');
        this.passwordForm.reset();
      });
  }

  onReject(): void {
    this.messageService.clear('passwordChange');
  }

  encodeAvatar(event: Event): void {
    this.b64.toBase64(event).then((b64: string): void => {
      this.#b64Avatar = b64;
    });
  }

  updateAvatar() {
    let student: IUpdateStudent = {
      avatar: this.#b64Avatar,
      name: this.student.name!,
      surnames: this.student.surnames!,
      nickname: this.student.nickname!,
      birth_date: this.student.birth_date.toString()!,
    };

    this.studentService
      .update(this.student.id, student)
      .subscribe((response) => {});
    this.student.avatar = this.#b64Avatar;

    this.show = false;
  }

  toggle() {
    this.show = true;
  }

  joinRanking(): void {
    const code: string = this.codeForm.value.code ?? '';

    if (!this.#isValidUuid(code)) {
      return;
    }

    const entity: ICreateStudentAssignation = {
      code: code,
      student_id: this.student.id,
    };

    this.rankingService.assignStudent(entity)
      .subscribe(response => {
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
    this.loading = true;

    this.rankingService
      .leaderboardsForStudent(this.student.id)
      .subscribe((rankings: IRanking[]): void => {
        this.rankings = rankings;

        this.rankings.forEach((rank: IRanking): void => {
          rank.students.sort((a: IStudent, b: IStudent) => {
            return (
              b.pivot.points - a.pivot.points
              || a.nickname.localeCompare(b.nickname)
            );
          });
        });

        this.loading = false;
      });
  }
}
