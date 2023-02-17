import {Component} from '@angular/core';
import {AbstractControl, FormBuilder, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {IFormStudent} from '../../../models/form/form-student';
import {IFormTeacher} from '../../../models/form/form-teacher';
import {RegistrationService} from '../../services/registration.service';

@Component({
  selector: 'app-registration-form',
  templateUrl: './registration-form.component.html',
  styleUrls: ['./registration-form.component.scss'],
})
export class RegistrationFormComponent {
  constructor(private register: RegistrationService, private fb: FormBuilder, private router: Router) {
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
    birth_date: [<Date | null>null],
    center: [''],
  });

  get formControl(): { [key: string]: AbstractControl } {
    return this.form.controls;
  }

  submit(): void {
    debugger;
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
    let student: IFormStudent = {
      name: formValue.name!,
      surnames: formValue.surnames!,
      nickname: formValue.nickname!,
      email: formValue.email!,
      password: formValue.password!,
      password_confirmation: formValue.password_confirmation!,
      birth_date: formValue.birth_date!,
    };

    this.register.registerStudent(student)
      .subscribe(response => {
        this.router.navigate(['/login']);
      });
  }

  #registerTeacher(): void {
    const formValue = this.form.value;
    let teacher: IFormTeacher = {
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
