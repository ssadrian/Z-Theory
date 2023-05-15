import { HttpClient, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ICreateTeacher } from '../../../models/create/create-teacher';
import { ITeacher } from '../../../models/teacher.model';
import { IUpdatePassword } from '../../../models/update/update-password';
import { IUpdateTeacher } from '../../../models/update/update-teacher';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class TeacherService {
  constructor(private http: HttpClient) {}

  readonly #teacherUrl: string = `${environment.apiUrl}/teacher`;

  all(): Observable<ITeacher[]> {
    return this.http.get<ITeacher[]>(this.#teacherUrl);
  }

  get(id: number): Observable<ITeacher | null> {
    const url: string = `${this.#teacherUrl}/${id}`;
    return this.http.get<ITeacher>(url);
  }

  create(entity: ICreateTeacher): Observable<HttpResponse<Object>> {
    return this.http.post<HttpResponse<Object>>(this.#teacherUrl, entity, {
      observe: 'body',
    });
  }

  updatePassword(entity: IUpdatePassword): Observable<HttpResponse<Object>> {
    const url: string = `${this.#teacherUrl}/password`;
    return this.http.post(url, entity, {
      observe: 'response',
    });
  }

  update(id: number, entity: IUpdateTeacher): Observable<HttpResponse<Object>> {
    const url: string = `${this.#teacherUrl}/${id}`;
    return this.http.put(url, entity, {
      observe: 'response',
    });
  }

  delete(id: number): Observable<HttpResponse<Object>> {
    const url: string = `${this.#teacherUrl}/${id}`;
    return this.http.delete(url, {
      observe: 'response',
    });
  }
}
