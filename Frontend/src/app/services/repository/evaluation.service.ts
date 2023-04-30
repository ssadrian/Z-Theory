import { HttpClient, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CredentialService } from '../credential.service';
import { environment } from '../../environments/environment';
import { Observable, catchError } from 'rxjs';
import { ICreateEvaluation } from 'src/models/create/create-evaluation';
import { IDeleteEvaluation } from 'src/models/delete/delete-evaluation';
import { IEvaluationHistory } from 'src/models/evaluation-history.model';

@Injectable({
  providedIn: 'root',
})
export class EvaluationService {
  constructor(
    private http: HttpClient,
    private credentials: CredentialService
  ) {
    this.#clientHeaders = {
      Authorization: `Bearer ${this.credentials.token}`,
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };
  }

  readonly #clientHeaders: { [header: string]: string };
  readonly #evaluateUrl: string = `${environment.apiUrl}/evaluation`;

  all(): Observable<IEvaluationHistory[]> {
    return this.http.get<IEvaluationHistory[]>(this.#evaluateUrl, {
      headers: this.#clientHeaders,
    });
  }

  create(entity: ICreateEvaluation): Observable<HttpResponse<Object>> {
    return this.http.post(this.#evaluateUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  delete(entity: IDeleteEvaluation): Observable<Object> {
    const url = `${this.#evaluateUrl}/${entity.id}`;
    return this.http.delete(url, {
      headers: this.#clientHeaders
    });
  }

  forTeacher(teacherId: number): Observable<IEvaluationHistory[]> {
    const url = `${this.#evaluateUrl}_history/for_teacher/${teacherId}`;
    return this.http.get<IEvaluationHistory[]>(url, {
      headers: this.#clientHeaders,
    });
  }
}
