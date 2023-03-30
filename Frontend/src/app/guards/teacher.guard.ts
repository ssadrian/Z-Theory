import {Injectable} from '@angular/core';
import {Route, UrlSegment, UrlTree} from '@angular/router';
import {map, Observable} from 'rxjs';
import {CredentialService} from '../services/credential.service';
import {HttpClient, HttpResponse} from '@angular/common/http';
import {ILoginResponse} from '../../models/login-response.model';
import {IStudent} from '../../models/student.model';
import {ITeacher} from '../../models/teacher.model';
import {environment} from '../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class TeacherGuard {
  constructor(private _http: HttpClient, private credentials: CredentialService) {
  }

  #loginUrl: string = `${environment.apiUrl}/login/teacher`;

  canMatch(
    route: Route,
    segments: UrlSegment[]): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    if (this.credentials.currentUser) {
      return this.credentials.role === 'teacher';
    }

    const token: string = '"4|uSFdMS2drOrGY3bTmkaaGC3WodjS7Q7InrsqFx2j"';
    const role: string = 'teacher';
    const user: IStudent | ITeacher | undefined = JSON.parse('{"id":1,"name":"Wilhelmine Sporer DVM","surnames":"Bashirian","email":"w@w","password":"$2y$10$oGGoaVO9HIQRwH1Xnc15uuO5BF5XJBzRrUF.xSyxA3x9V0tiLqaF6","nickname":"68949920-307a-3746-9479-14497af9ced1","avatar":"Corrupti.","center":"ipsum","created_at":"2023-03-29T18:15:16.000000Z","updated_at":"2023-03-29T18:15:16.000000Z"}');

    this.credentials.token = token;
    this.credentials.role = role;
    this.credentials.currentUser = user;
    return true;

    return this._http.post<ILoginResponse>(this.#loginUrl, JSON.stringify({
      'email': this.credentials.email,
      'password': this.credentials.password,
    }), {
      headers: {
        'Content-Type': 'application/json',
      },
      observe: 'response',
    }).pipe(map((response: HttpResponse<ILoginResponse>): boolean => {
      if (response.status !== 200) {
        return false;
      }

      const token: string = response.body?.token ?? '';
      const role: string = response.body?.role ?? '';
      const user: IStudent | ITeacher | undefined = response.body?.user;

      this.credentials.token = token;
      this.credentials.role = role;
      this.credentials.currentUser = user;

      console.log(JSON.stringify(user));
      return role === 'teacher';
    }));
  }
}
