import {HttpClient, HttpResponse} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {IUpdatePassword} from 'src/models/update/update-password';
import {ICreateStudent} from '../../../models/create/create-student';
import {IStudent} from '../../../models/student.model';
import {IUpdateStudent} from '../../../models/update/update-student';
import {environment} from '../../environments/environment';
import {CredentialService} from '../credential.service';

@Injectable({
  providedIn: 'root',
})
export class StudentService {
  #studentUrl: string = `${environment.apiUrl}/student`;
  readonly #clientHeaders: { [header: string]: string };

  constructor(private http: HttpClient, private credentials: CredentialService) {
    this.#clientHeaders = {
      'Authorization': `Bearer ${this.credentials.token}`,
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };
  }

  all(): Observable<IStudent[]> {
    return this.http.get<IStudent[]>(this.#studentUrl, {
      headers: this.#clientHeaders,
    });
  }

  get(id: number): Observable<IStudent | null> {
    const url: string = `${this.#studentUrl}/${id}`;
    return this.http.get<IStudent>(url, {
      headers: this.#clientHeaders,
    });
  }

  create(entity: ICreateStudent): Observable<HttpResponse<Object>> {
    return this.http.post(this.#studentUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  updatePassword(entity: IUpdatePassword) {
    const url: string = `${this.#studentUrl}/password`;
    return this.http.post(url, entity, {
      headers: this.#clientHeaders,
    });
  }

  update(id: number, entity: IUpdateStudent): Observable<HttpResponse<Object>> {
    const url: string = `${this.#studentUrl}/${id}`;
    return this.http.put(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  delete(id: number): Observable<HttpResponse<Object>> {
    const url: string = `${this.#studentUrl}/${id}`;
    return this.http.delete(url, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }
}
