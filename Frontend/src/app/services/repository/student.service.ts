import { HttpClient, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { IUpdatePassword } from 'src/models/update/update-password';
import { ICreateStudent } from '../../../models/create/create-student';
import { IStudent } from '../../../models/student.model';
import { IUpdateStudent } from '../../../models/update/update-student';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class StudentService {
  constructor(private http: HttpClient) {
  }

  readonly #studentUrl: string = `${environment.apiUrl}/student`;

  all(): Observable<IStudent[]> {
    return this.http.get<IStudent[]>(this.#studentUrl);
  }

  get(id: number): Observable<IStudent | null> {
    const url: string = `${this.#studentUrl}/${id}`;
    return this.http.get<IStudent>(url);
  }

  create(entity: ICreateStudent): Observable<HttpResponse<Object>> {
    return this.http.post(this.#studentUrl, entity, {
      observe: 'response',
    });
  }

  updatePassword(entity: IUpdatePassword) {
    const url: string = `${this.#studentUrl}/password`;
    return this.http.post(url, entity);
  }

  update(id: number, entity: IUpdateStudent): Observable<HttpResponse<Object>> {
    const url: string = `${this.#studentUrl}/${id}`;
    return this.http.put(url, entity, {
      observe: 'response',
    });
  }

  delete(id: number): Observable<HttpResponse<Object>> {
    const url: string = `${this.#studentUrl}/${id}`;
    return this.http.delete(url, {
      observe: 'response',
    });
  }
}
