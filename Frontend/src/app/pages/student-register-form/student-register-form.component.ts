import { Component, OnInit } from '@angular/core';
import { AbstractControl, FormBuilder, Validators } from '@angular/forms';
import { IFormStudent } from 'src/models/form/form-student';
import { RegistrationService } from "../../services/registration.service";

@Component({
  selector: 'app-student-register-form',
  templateUrl: './student-register-form.component.html',
  styleUrls: ['./student-register-form.component.scss']
})
export class StudentRegisterFormComponent {
  constructor(private register: RegistrationService, private fb: FormBuilder) { }

  isSubmit: boolean = false

  studentForm = this.fb.group({
    nickname: ["", [Validators.required]],
    birth_date: [new Date, [Validators.required]],
    name: ["", [Validators.required]],
    surnames: ["", [Validators.required]],
    password: ["", [Validators.required]],
    password_confirmation: ["", [Validators.required]],
    email: ["", [Validators.required, Validators.email]],
    tos: [false, [Validators.requiredTrue]]
  })

  get formControl(): { [key: string]: AbstractControl } {
    return this.studentForm.controls;
  }

  submit(): void {
    this.isSubmit = true;

    if (!this.studentForm.valid) {
      return;
    }

    let formValue = this.studentForm.value;
    let student: IFormStudent = {
      name: formValue.name!,
      surnames: formValue.surnames!,
      nickname: formValue.nickname!,
      email: formValue.email!,
      password: formValue.password!,
      password_confirmation: formValue.password_confirmation!,
      birth_date: formValue.birth_date!,
    };

    console.log(JSON.stringify(formValue));
    console.log(this.register.registerStudent(student));
  }



}
