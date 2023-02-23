import {Component} from '@angular/core';
import {AbstractControl, FormBuilder, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {RegistrationService} from '../../services/registration.service';
import {Base64Service} from '../../services/base64.service';
import {ICreateStudent} from '../../../models/create/create-student';
import {ICreateTeacher} from '../../../models/create/create-teacher';

@Component({
  selector: 'app-registration-form',
  templateUrl: './registration-form.component.html',
  styleUrls: ['./registration-form.component.scss'],
})
export class RegistrationFormComponent {
  constructor(
    private register: RegistrationService,
    private fb: FormBuilder,
    private router: Router, private b64: Base64Service) {
  }

  isSubmit: boolean = false;

  isTeacherRegistering?: boolean;

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

  get formControl(): { [key: string]: AbstractControl } {
    return this.form.controls;
  }

  encodeAvatar(event: Event): void {
    this.b64.toBase64(event)
      .then((b64: string): void => {
        this.#b64Avatar = b64;
      });
  }

  submit(): void {
    this.isSubmit = true;

    if (!this.form.valid) {
      return;
    }

    if (this.isTeacherRegistering) {
      this.#registerTeacher();
      return;
    }

    this.#registerStudent();
  }

  #registerStudent(): void {
    const formValue = this.form.value;
    let student: ICreateStudent = {
      avatar: this.#b64Avatar,
      name: formValue.name!,
      surnames: formValue.surnames!,
      nickname: formValue.nickname!,
      email: formValue.email!,
      password: formValue.password!,
      password_confirmation: formValue.password_confirmation!,
      birth_date: formValue.birth_date!
    };

    this.register.registerStudent(student)
      .subscribe(response => {
        this.router.navigate(['/login']);
      });
  }

  #registerTeacher(): void {
    const formValue = this.form.value;
    let teacher: ICreateTeacher = {
      avatar: this.#b64Avatar,
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
