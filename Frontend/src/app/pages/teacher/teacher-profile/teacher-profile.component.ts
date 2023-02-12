import { Component } from '@angular/core';
import {ITeacher} from "../../../../models/teacher.model";
import {CredentialService} from "../../../services/credential.service";

@Component({
  selector: 'app-teacher-profile',
  templateUrl: './teacher-profile.component.html',
  styleUrls: ['./teacher-profile.component.scss']
})
export class TeacherProfileComponent {
  constructor(private credentials: CredentialService) {
  }

  teacher: ITeacher = this.credentials.currentUser as ITeacher;
}
