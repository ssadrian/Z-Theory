import {HttpClient, HttpResponse} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {ICreateTeacher} from '../../../models/create/create-teacher';
import {ITeacher} from '../../../models/teacher.model';
import {IUpdatePassword} from '../../../models/update/update-password';
import {IUpdateTeacher} from '../../../models/update/update-teacher';
import {environment} from '../../environments/environment';
import {CredentialService} from '../credential.service';

@Injectable({
  providedIn: 'root',
})
export class TeacherService {
  #teacherUrl: string = `${environment.apiUrl}/teacher`;
  readonly #clientHeaders: { [header: string]: string };

  constructor(private http: HttpClient, private credentials: CredentialService) {
    this.#clientHeaders = {
      Authorization: `Bearer ${this.credentials.token}`,
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };
  }

  all(): Observable<ITeacher[]> {
    return this.http.get<ITeacher[]>(this.#teacherUrl, {
      headers: this.#clientHeaders,
    });
  }

  get(id: number): Observable<ITeacher | null> {
    const url: string = `${this.#teacherUrl}/${id}`;
    return this.http.get<ITeacher>(url, {
      headers: this.#clientHeaders,
    });
  }

  create(entity: ICreateTeacher): Observable<HttpResponse<Object>> {
    return this.http.post(this.#teacherUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  updatePassword(entity: IUpdatePassword): Observable<HttpResponse<Object>> {
    const url: string = `${this.#teacherUrl}/password`;
    return this.http.post(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response'
    });
  }

  update(id: number, entity: IUpdateTeacher): Observable<HttpResponse<Object>> {
    const url: string = `${this.#teacherUrl}/${id}`;
    return this.http.put(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  delete(id: number): Observable<HttpResponse<Object>> {
    const url: string = `${this.#teacherUrl}/${id}`;
    return this.http.delete(url, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }
}
