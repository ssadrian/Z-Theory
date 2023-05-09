import { Injectable } from '@angular/core';
import { IStudent } from '../../models/student.model';
import { ITeacher } from '../../models/teacher.model';
import { ISessionCookie } from 'src/models/session-cookie.model';

@Injectable({
  providedIn: 'root',
})
export class CredentialService {
  email: string = '';
  password: string = '';
  token: string = '';
  role: 'student' | 'teacher' | '' = '';

  currentUser?: IStudent | ITeacher;

  set(values: ISessionCookie) {
    this.email = values.email;
    this.password = values.password;
    this.role = values.role;
    this.currentUser = values.currentUser;
  }

  clear(): void {
    this.email = '';
    this.password = '';
    this.token = '';
    this.role = '';
    this.currentUser = undefined;
  }
}
