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
  #apiRegistrationUrl: string = `${ environment.apiUrl }/registration`;

  constructor(private _http: HttpClient) {
  }

  registerStudent(student: IFormStudent): Observable<Object> {
    const url: string = this.#apiRegistrationUrl + "/student";
    return this._http.post(url, student);
  }

  registerTeacher(teacher: IFormTeacher) {
    const url: string = this.#apiRegistrationUrl + "/teacher";
    return this._http.post(url, teacher);
  }
}
