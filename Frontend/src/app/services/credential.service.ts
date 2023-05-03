import { Injectable } from '@angular/core';
import { IStudent } from '../../models/student.model';
import { ITeacher } from '../../models/teacher.model';

@Injectable({
  providedIn: 'root',
})
export class CredentialService {
  email: string = '';
  password: string = '';
  role: string = '';
  token: string = '';

  currentUser?: IStudent | ITeacher;

  clear(): void {
    this.email = '';
    this.password = '';
    this.token = '';
    this.currentUser = undefined;
  }
}
