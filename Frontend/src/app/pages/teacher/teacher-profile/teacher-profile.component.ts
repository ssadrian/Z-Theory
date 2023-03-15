import { Component, OnInit } from '@angular/core';
import { AbstractControl, FormBuilder, Validators } from '@angular/forms';
import { MessageService } from 'primeng/api';
import { Base64Service } from 'src/app/services/base64.service';
import { RankingService } from 'src/app/services/repository/ranking.service';
import { StudentService } from 'src/app/services/repository/student.service';
import { IStudent } from 'src/models/student.model';
import { IUpdateRanking } from 'src/models/update/update-ranking';
import { IUpdateTeacher } from 'src/models/update/update-teacher';
import { IUser } from 'src/models/user.model';
import { v4 as uuidv4 } from 'uuid';
import { IRanking } from '../../../../models/ranking.model';
import { ITeacher } from '../../../../models/teacher.model';
import { IUpdatePassword } from '../../../../models/update/update-password';
import { CredentialService } from '../../../services/credential.service';
import { TeacherService } from '../../../services/repository/teacher.service';

@Component({
  selector: 'app-teacher-profile',
  templateUrl: './teacher-profile.component.html',
  styleUrls: ['./teacher-profile.component.scss'],
})
export class TeacherProfileComponent implements OnInit {
  constructor(
    private teacherService: TeacherService,
    private credentials: CredentialService,
    private fb: FormBuilder,
    private rankingService: RankingService,
    private messageService: MessageService,
    private b64: Base64Service,
    private studentService: StudentService
  ) {}

  show: boolean = false;

  teacher: ITeacher = this.credentials.currentUser as ITeacher;
  createdRankings: IRanking[] = [];
  isSubmit: boolean = false;
  loading: boolean = true;

  createRankingForm = this.fb.group({
    name: ['', [Validators.required]],
  });

  passwordForm = this.fb.group({
    password: ['', [Validators.required]],
    new_password: ['', [Validators.required]],
  });

  get formControl(): { [key: string]: AbstractControl } {
    return this.createRankingForm.controls;
  }

  #b64Avatar: string = '';

  ngOnInit(): void {
    this.#updateCreatedRanks();
  }

  submit(): void {
    this.isSubmit = true;

    const formValue = this.createRankingForm.value;
    this.rankingService
      .create({
        code: uuidv4(),
        creator: this.teacher.id,
        name: formValue.name!,
      })
      .subscribe(this.#updateCreatedRanks);
  }

  showPasswordChangeForm(): void {
    this.messageService.add({
      key: 'passwordChange',
      sticky: true,
      severity: 'info',
      summary: 'Cambiar Contraseña',
    });
  }

  changePassword(): void {
    const formValues = this.passwordForm.value;
    const entity: IUpdatePassword = {
      id: this.credentials.currentUser?.id!,
      password: formValues.password!,
      new_password: formValues.new_password!,
    };

    this.teacherService.updatePassword(entity).subscribe((): void => {
      this.messageService.clear('passwordChange');
      this.passwordForm.reset();
    });
  }

  onReject(): void {
    this.messageService.clear('passwordChange');
  }

  #updateCreatedRanks(): void {
    this.loading = true;

    this.rankingService
      .createdBy(this.teacher.id)
      .subscribe((rankings: IRanking[]): void => {
        this.createdRankings = rankings;

        this.createdRankings.forEach((rank: IRanking): void => {
          rank.students.sort((a: IStudent, b: IStudent) => {
            return (
              b.pivot.points - a.pivot.points ||
              a.nickname.localeCompare(b.nickname)
            );
          });
        });
        this.loading = false;
      });
  }

  encodeAvatar(event: Event): void {
    this.b64.toBase64(event).then((b64: string): void => {
      this.#b64Avatar = b64;
    });
  }

  updateAvatar(): void {
    const entity: IUpdateTeacher = {
      avatar: this.#b64Avatar,
      name: this.teacher.name!,
      surnames: this.teacher.surnames!,
      nickname: this.teacher.nickname!,
      center: this.teacher.center!,
    };

    this.teacherService.update(this.teacher.id, entity).subscribe();
    this.teacher.avatar = this.#b64Avatar;

    this.show = false;
  }

  toggle() {
    this.show = true;
  }

  changeRankingId(ranking: IRanking): void {
    const ranking_code: string = uuidv4();
    const entity: IUpdateRanking = {
      code: ranking_code,
      old_code: ranking.code,
      name: ranking.name,
      creator: ranking.creator,
    };

    this.rankingService.update(entity).subscribe();
  }

  deleteStudent(student: IUser): void {
    this.studentService.delete(student.id).subscribe();
  }
}
