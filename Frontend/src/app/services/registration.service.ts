import {Injectable} from '@angular/core';
import {IStudent} from "../../models/student.model";
import {ITeacher} from "../../models/teacher.model";
import {environment} from "../environments/environment";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class RegistrationService {
  #apiRegistrationUrl: string = `${environment.apiUrl}/registration`;

  constructor(private _http: HttpClient) {
  }

  registerStudent(student: IStudent): Observable<Object> {
    const url: string = this.#apiRegistrationUrl + "/student";
    return this._http.post(url, student);
  }

  registerTeacher(teacher: ITeacher) {
    const url: string = this.#apiRegistrationUrl + "/teacher";
    return this._http.post(url, teacher);
  }
}
