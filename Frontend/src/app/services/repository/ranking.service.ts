import { HttpClient, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ICreateRanking } from '../../../models/create/create-ranking';
import { ICreateStudentAssignation } from '../../../models/create/create-student-assignation';
import { IRanking } from '../../../models/ranking.model';
import { IAcceptStudentAssignation } from '../../../models/update/accept-student-assignation';
import { IUpdateRanking } from '../../../models/update/update-ranking';
import { IUpdateRankingStudent } from '../../../models/update/update-ranking-student';
import { environment } from '../../environments/environment';
import { CredentialService } from '../credential.service';
import { IDeclineStudentAssignation } from '../../../models/update/decline-student-assignation';

@Injectable({
  providedIn: 'root',
})
export class RankingService {
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
  readonly #rankingUrl: string = `${environment.apiUrl}/ranking`;

  all(): Observable<IRanking[]> {
    return this.http.get<IRanking[]>(this.#rankingUrl, {
      headers: this.#clientHeaders,
    });
  }

  get(code: string): Observable<IRanking | null> {
    const url: string = `${this.#rankingUrl}/${code}`;
    return this.http.get<IRanking>(url, {
      headers: this.#clientHeaders,
    });
  }

  createdBy(id: number): Observable<IRanking[]> {
    const url: string = `${this.#rankingUrl}/created_by/${id}`;
    return this.http.get<IRanking[]>(url, {
      headers: this.#clientHeaders,
    });
  }

  assignStudent(
    entity: ICreateStudentAssignation
  ): Observable<HttpResponse<Object>> {
    const url: string = `${this.#rankingUrl}/assign/${entity.student_id}`;

    return this.http.post(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  acceptStudent(
    entity: IAcceptStudentAssignation
  ): Observable<HttpResponse<Object>> {
    const url: string = `${this.#rankingUrl}/accept/${entity.url_studentId}`;
    return this.http.post(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  declineStudent(
    entity: IDeclineStudentAssignation
  ): Observable<HttpResponse<Object>> {
    const url: string = `${this.#rankingUrl}/decline/${entity.url_studentId}`;
    return this.http.post(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  leaderboardsForStudent(id: number): Observable<IRanking[]> {
    const url: string = `${this.#rankingUrl}/for/${id}`;
    return this.http.get<IRanking[]>(url, {
      headers: this.#clientHeaders,
    });
  }

  create(entity: ICreateRanking): Observable<HttpResponse<Object>> {
    return this.http.post(this.#rankingUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  update(entity: IUpdateRanking): Observable<HttpResponse<Object>> {
    const url: string = `${this.#rankingUrl}/${entity.url_oldCode}`;
    return this.http.put(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  delete(code: string): Observable<HttpResponse<Object>> {
    const url: string = `${this.#rankingUrl}/${code}`;
    return this.http.delete(url, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  updateForStudent(entity: IUpdateRankingStudent): Observable<Object> {
    const url: string = `${this.#rankingUrl}/${entity.url_rankingCode}/for/${
      entity.url_studentId
    }`;
    return this.http.put(url, entity, {
      headers: this.#clientHeaders,
    });
  }

  queuesForTeacher(teacherId: number): Observable<IRanking[]> {
    const url: string = `${this.#rankingUrl}/queues/for/${teacherId}`;
    return this.http.get<IRanking[]>(url, {
      headers: this.#clientHeaders,
    });
  }
}
