import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ICreateStudent } from '../../models/create/create-student';
import { ICreateTeacher } from '../../models/create/create-teacher';
import { environment } from '../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class RegistrationService {
  #apiRegistrationUrl: string = `${environment.apiUrl}`;

  constructor(private _http: HttpClient) {}

  registerStudent(student: ICreateStudent): Observable<any> {
    return this.#register('/student/register', student);
  }

  registerTeacher(teacher: ICreateTeacher): Observable<any> {
    return this.#register('/teacher/register', teacher);
  }

  #register(
    endpoint: string,
    formUser: ICreateStudent | ICreateTeacher
  ): Observable<any> {
    const url: string = this.#apiRegistrationUrl + endpoint;

    return this._http.post(url, JSON.stringify(formUser), {
      headers: {
        'Content-Type': 'application/json',
      },
    });
  }
}
