import {Injectable} from "@angular/core";
import {environment} from "../environments/environment";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {ICreateStudent} from '../../models/create/create-student';
import {ICreateTeacher} from '../../models/create/create-teacher';

@Injectable({
  providedIn: "root",
})
export class RegistrationService {
  #apiRegistrationUrl: string = `${environment.apiUrl}/register`;

  constructor(private _http: HttpClient) {
  }

  registerStudent(student: ICreateStudent): Observable<any> {
    return this.#register("/student", student);
  }

  registerTeacher(teacher: ICreateTeacher): Observable<any> {
    return this.#register("/teacher", teacher);
  }

  #register(endpoint: string, formUser: ICreateStudent | ICreateTeacher): Observable<any> {
    const url: string = this.#apiRegistrationUrl + endpoint;

    return this._http.post(url, JSON.stringify(formUser), {
      headers: {
        'Content-Type': 'application/json'
      }
    });
  }
}
