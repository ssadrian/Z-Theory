import { Component, OnInit } from '@angular/core';
import {RegistrationService} from "../../services/registration.service";

@Component({
  selector: 'app-student-register-form',
  templateUrl: './student-register-form.component.html',
  styleUrls: ['./student-register-form.component.scss']
})
export class StudentRegisterFormComponent implements OnInit {
  constructor(private register: RegistrationService) { }

  ngOnInit(): void {
  }
}
