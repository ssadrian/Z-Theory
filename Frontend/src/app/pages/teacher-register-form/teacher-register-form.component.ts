import { Component, OnInit } from '@angular/core';
import {
  AbstractControl,
  FormControl,
  FormGroup,
  Validators,
} from '@angular/forms';

@Component({
  selector: 'app-teacher-register-form',
  templateUrl: './teacher-register-form.component.html',
  styleUrls: ['./teacher-register-form.component.scss'],
})
export class TeacherRegisterFormComponent implements OnInit {
  isSubmit: boolean = false;

  constructor() {}

  ngOnInit(): void {}

  teacherForm = new FormGroup({
    nickname: new FormControl('', [Validators.required]),
    center: new FormControl('', [Validators.required]),
    name: new FormControl('', [Validators.required]),
    surnames: new FormControl('', [Validators.required]),
    password: new FormControl('', [Validators.required]),
    password_confirmation: new FormControl('', [Validators.required]),
    email: new FormControl('', [Validators.required, Validators.email]),
    tos: new FormControl(false, [Validators.requiredTrue]), //Terms Of Service
  });

  //Con este get creamos una key para controlar los errores del formulario

  get formControl(): { [key: string]: AbstractControl } {
    return this.teacherForm.controls;
  }

  submit() {
    this.isSubmit = true;
    if (!this.teacherForm.valid) {
    } else {
      console.log(
        'El formulario es válido! Estos son los datos: ' +
          JSON.stringify(this.teacherForm.value)
      );
    }
  }
}
