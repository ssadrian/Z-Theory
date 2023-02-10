import {HttpClient} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {environment} from '../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class LoginService {
  constructor(private _http: HttpClient) {
  }

  #loginUrl: string = `${environment.apiUrl}/login`;

  login(credentials: { email: string, password: string }): Observable<any> {
    return this._http.post(this.#loginUrl, JSON.stringify(credentials), {
      headers: {
        'Content-Type': 'application/json',
      }
    });
  }
}
