import {Component} from '@angular/core';
import {AbstractControl, FormBuilder, Validators} from '@angular/forms';
import {Base64Service} from '../../../services/base64.service';
import {RegistrationService} from '../../../services/registration.service';
import {IFormTeacher} from '../../../../models/form/form-teacher';
import {Router} from '@angular/router';

@Component({
  selector: 'app-teacher-register-form',
  templateUrl: './teacher-register-form.component.html',
  styleUrls: ['./teacher-register-form.component.scss'],
})
export class TeacherRegisterFormComponent {
  constructor(
    private fb: FormBuilder,
    private register: RegistrationService,
    private router: Router,
    public b64: Base64Service) {
  }

  isSubmit: boolean = false;

  teacherForm = this.fb.group({
    nickname: ['', [Validators.required]],
    center: ['', [Validators.required]],
    name: ['', [Validators.required]],
    surnames: ['', [Validators.required]],
    password: ['', [Validators.required]],
    password_confirmation: ['', [Validators.required]],
    email: ['', [Validators.required, Validators.email]],
    avatar: [''],
    tos: [false, [Validators.requiredTrue]],
  });

  // Con este get creamos una key para controlar los errores del formulario
  get formControl(): { [key: string]: AbstractControl } {
    return this.teacherForm.controls;
  }

  encodeAvatar(event: Event): void {
    this.b64.setToBase64(event, this.teacherForm.value.avatar);
  }

  submit(): void {
    this.isSubmit = true;

    if (!this.teacherForm.valid) {
      return;
    }

    let formValue = this.teacherForm.value;
    let teacher: IFormTeacher = {
      avatar: formValue.avatar!,
      name: formValue.name!,
      surnames: formValue.surnames!,
      nickname: formValue.nickname!,
      email: formValue.email!,
      password: formValue.password!,
      password_confirmation: formValue.password_confirmation!,
      center: formValue.center!,
    };

    this.register.registerTeacher(teacher)
      .subscribe(response => {
        this.router.navigate(['/login']);
      });
  }
}
