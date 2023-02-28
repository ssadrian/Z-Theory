import {HttpClient, HttpResponse} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {ICreateRanking} from '../../../models/create/create-ranking';
import {IRanking} from '../../../models/ranking.model';
import {IUpdateRanking} from '../../../models/update/update-ranking';
import {environment} from '../../environments/environment';
import {CredentialService} from '../credential.service';
import {IRepository} from '../../../models/patterns/repository/repository.model';

@Injectable({
  providedIn: 'root',
})
export class RankingService {
  #rankingUrl: string = `${environment.apiUrl}/ranking`;
  readonly #clientHeaders: { [header: string]: string };

  constructor(private http: HttpClient, private credentials: CredentialService) {
    this.#clientHeaders = {
      'Authorization': `Bearer ${this.credentials.token}`,
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };
  }

  all(): Observable<IRanking[]> {
    return this.http.get<IRanking[]>(this.#rankingUrl, {
      headers: this.#clientHeaders,
    });
  }

  get(id: number): Observable<IRanking | null> {
    const url: string = `${this.#rankingUrl}/${id}`;
    return this.http.get<IRanking>(url, {
      headers: this.#clientHeaders,
    });
  }

  create(entity: ICreateRanking): Observable<HttpResponse<Object>> {
    return this.http.post(this.#rankingUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  update(id: number, entity: IUpdateRanking): Observable<HttpResponse<Object>> {
    const url: string = `${this.#rankingUrl}/${id}`;
    return this.http.put(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  delete(id: number): Observable<HttpResponse<Object>> {
    const url: string = `${this.#rankingUrl}/${id}`;
    return this.http.delete(url, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }
}
