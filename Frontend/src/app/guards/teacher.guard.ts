import { Injectable } from '@angular/core';
import { Route, UrlSegment, UrlTree } from '@angular/router';
import { map, Observable } from 'rxjs';
import { CredentialService } from '../services/credential.service';
import { HttpClient, HttpResponse } from '@angular/common/http';
import { ILoginResponse } from '../../models/login-response.model';
import { IStudent } from '../../models/student.model';
import { ITeacher } from '../../models/teacher.model';
import {
  environment,
  studentPass,
  teacherPass,
} from '../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class TeacherGuard {
  constructor(
    private _http: HttpClient,
    private credentials: CredentialService
  ) {}

  #loginUrl: string = `${environment.apiUrl}/login/teacher`;

  canMatch(
    route: Route,
    segments: UrlSegment[]
  ):
    | Observable<boolean | UrlTree>
    | Promise<boolean | UrlTree>
    | boolean
    | UrlTree {
    if (studentPass()) {
      return false;
    }

    if (teacherPass()) {
      this.credentials.token = '16|R7vyPUDatEyMtVN77yBwcHhcUwvfDdfNYdEZNbve';
      this.credentials.role = 'teacher';
      this.credentials.currentUser = {
        id: 1,
        name: 'Helena Crist',
        surnames: 'Jerde',
        email: 'w@w',
        password:
          '$2y$10$kFwETND7M2smscOBAv12B.QyXQ.3W9RX3yOqSAcSRlbIm01xa1Mvu',
        nickname: '9688913c-82d3-37f3-bf1d-29bc4b0fff8d',
        avatar: 'None',
        center: 'None',
        created_at: '2000-01-01T12:00:00.000000Z',
        updated_at: '2000-01-01T12:00:00.000000Z',
      } as ITeacher;

      return true;
    }

    if (this.credentials.currentUser) {
      return this.credentials.role === 'teacher';
    }

    return this._http
      .post<ILoginResponse>(
        this.#loginUrl,
        JSON.stringify({
          email: this.credentials.email,
          password: this.credentials.password,
        }),
        {
          headers: {
            'Content-Type': 'application/json',
          },
          observe: 'response',
        }
      )
      .pipe(
        map((res: HttpResponse<ILoginResponse>): boolean => {
          const token: string = res.body?.token ?? '';
          const role: string = res.body?.role ?? '';
          const user: IStudent | ITeacher | undefined = res.body?.user;

          if (token === '') {
            return false;
          }

          this.credentials.token = token;
          this.credentials.role = role;
          this.credentials.currentUser = user;

          return role === 'teacher';
        })
      );
  }
}
