import { Component } from '@angular/core';
import { Validators, AbstractControl, FormBuilder } from '@angular/forms';
import { RankingService } from 'src/app/services/repository/ranking.service';
import { ITeacher } from '../../../../models/teacher.model';
import { CredentialService } from '../../../services/credential.service';
import { v4 as uuidv4 } from 'uuid';
import {TeacherService} from '../../../services/repository/teacher.service';
import {IUpdatePassword} from '../../../../models/update/update-password';
import {MessageService} from 'primeng/api';

@Component({
  selector: 'app-teacher-profile',
  templateUrl: './teacher-profile.component.html',
  styleUrls: ['./teacher-profile.component.scss'],
})
export class TeacherProfileComponent {
  constructor(
    private teacherService: TeacherService,
    private credentials: CredentialService,
    private fb: FormBuilder,
    private rankingService: RankingService,
    private messageService: MessageService
  ) {}

  teacher: ITeacher = this.credentials.currentUser as ITeacher;
  isSubmit: boolean = false;

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

  submit(): void {
    this.isSubmit = true;

    const formValue = this.createRankingForm.value;
    this.rankingService
      .create({
        name: formValue.name!,
        teachers_id: this.teacher.id,
        code: uuidv4(),
        creator: this.teacher.id
      })
      .subscribe();
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

    this.teacherService.updatePassword(entity)
      .subscribe(response => {
        this.messageService.clear('passwordChange');
        this.passwordForm.reset();
      });
  }

  onReject(): void {
    this.messageService.clear('passwordChange');
  }
}
