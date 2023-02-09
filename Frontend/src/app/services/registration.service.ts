import {Injectable} from "@angular/core";
import {environment} from "../environments/environment";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {IFormTeacher} from "../../models/form/form-teacher";
import {IFormStudent} from "../../models/form/form-student";

@Injectable({
  providedIn: "root",
})
export class RegistrationService {
  #apiRegistrationUrl: string = `${environment.apiUrl}/register`;

  constructor(private _http: HttpClient) {
  }

  registerStudent(student: IFormStudent): Observable<any> {
    return this.#register("/student", student);
  }

  registerTeacher(teacher: IFormTeacher): Observable<any> {
    return this.#register("/teacher", teacher);
  }

  #register(endpoint: string, formUser: IFormStudent | IFormTeacher): Observable<any> {
    const url: string = this.#apiRegistrationUrl + endpoint;

    return this._http.post(url, JSON.stringify(formUser), {
      headers: {
        'Content-Type': 'application/json'
      }
    });
  }
}
