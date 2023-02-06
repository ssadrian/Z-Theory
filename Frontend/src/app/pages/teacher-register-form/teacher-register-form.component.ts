import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-teacher-register-form',
  templateUrl: './teacher-register-form.component.html',
  styleUrls: ['./teacher-register-form.component.scss'],
})
export class TeacherRegisterFormComponent implements OnInit {
  constructor() {}

  ngOnInit(): void {}

  teacherForm = new FormGroup({
    nickname: new FormControl('', [Validators.required]),
    center: new FormControl('', [Validators.required]),
    name: new FormControl('', [Validators.required]),
    surnames: new FormControl('', [Validators.required]),
    password: new FormControl('', [Validators.required]),
    password_confirmation: new FormControl('', [Validators.required]),
    email: new FormControl('', [Validators.required]),
    tos: new FormControl('', [Validators.required]), //Terms Of Service
  });

  submit() {
    if (!this.teacherForm.valid) {

    } else {

      console.log("El formulario es válido! Estos son los datos: " + this.teacherForm.value);
      
      //TODO: El formulario es válido, enviar datos
      

    }
  }


}
