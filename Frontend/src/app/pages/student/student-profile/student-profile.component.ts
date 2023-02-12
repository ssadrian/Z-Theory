import { Component } from '@angular/core';
import {CredentialService} from "../../../services/credential.service";
import {IStudent} from "../../../../models/student.model";

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss']
})
export class StudentProfileComponent {
  constructor(private credentials: CredentialService) {
  }

  student: IStudent = this.credentials.currentUser as IStudent;
}
