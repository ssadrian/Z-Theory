import {Injectable} from "@angular/core";
import {CanMatch, Route, UrlSegment, UrlTree} from "@angular/router";
import {map, Observable} from "rxjs";
import {CredentialService} from "../services/credential.service";
import {HttpClient, HttpResponse} from "@angular/common/http";
import {ILoginResponse} from "../../models/login-response.model";
import {IStudent} from "../../models/student.model";
import {ITeacher} from "../../models/teacher.model";
import {environment} from "../environments/environment";

@Injectable({
  providedIn: "root",
})
export class TeacherGuard implements CanMatch {
  constructor(private _http: HttpClient, private credentials: CredentialService) {
  }

  #loginUrl: string = `${ environment.apiUrl }/login/teacher`;

  canMatch(
    route: Route,
    segments: UrlSegment[]): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    if (this.credentials.currentUser) {
      return this.credentials.role === "teacher";
    }

    return this._http.post<ILoginResponse>(this.#loginUrl, JSON.stringify({
      "email": this.credentials.email,
      "password": this.credentials.password,
    }), {
      headers: {
        "Content-Type": "application/json",
      },
      observe: "response",
    }).pipe(map((response: HttpResponse<ILoginResponse>): boolean => {
      if (response.status !== 200) {
        return false;
      }

      const token: string = response.body?.token ?? "";
      const role: string = response.body?.role ?? "";
      const user: IStudent | ITeacher | undefined = response.body?.user;

      this.credentials.token = token;
      this.credentials.role = role;
      this.credentials.currentUser = user;

      return role === "teacher";
    }));
  }
}
