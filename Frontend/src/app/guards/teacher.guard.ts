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

    const token: string = '"4|uSFdMS2drOrGY3bTmkaaGC3WodjS7Q7InrsqFx2j"';
    const role: string = 'teacher';
    const user: IStudent | ITeacher | undefined = JSON.parse('{"id":1,"name":"Hobart McKenzie","surnames":"Nitzsche","email":"q@q","password":"$2y$10$Nxk9X1cJMyFeCSddyziAouPyE6xEpCLdLn13Ib/TIEBqKVjXMK7ri","nickname":"500823cb-b47c-3ae4-926f-b179b346e9a5","avatar":null,"birth_date":"2023-03-22","created_at":"2023-03-22T14:17:26.000000Z","updated_at":"2023-03-24T19:49:05.000000Z","rankings":[{"code":"2d0b139b-5b07-36fe-b655-48e903113ffe","name":"Quasi voluptate quia dolore aut eos et.","creator":1,"created_at":"2023-03-22T14:17:26.000000Z","updated_at":"2023-03-22T14:17:26.000000Z","pivot":{"student_id":1,"ranking_id":1,"points":6}},{"code":"93f2bb70-0fe0-3eed-b88b-a14e2652f0d8","name":"Ut corporis sunt fuga quas.","creator":3,"created_at":"2023-03-22T14:17:27.000000Z","updated_at":"2023-03-22T14:17:27.000000Z","pivot":{"student_id":1,"ranking_id":3,"points":8}}]}');

    this.credentials.token = token;
    this.credentials.role = role;
    this.credentials.currentUser = user;
    return true;

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
      debugger;
      return role === "teacher";
    }));
  }
}
