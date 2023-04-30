import { HttpClient, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Route, UrlSegment, UrlTree } from '@angular/router';
import { map, Observable } from 'rxjs';
import { ILoginResponse } from '../../models/login-response.model';
import { IStudent } from '../../models/student.model';
import { ITeacher } from '../../models/teacher.model';
import {
  environment,
  studentPass,
  teacherPass,
} from '../environments/environment';
import { CredentialService } from '../services/credential.service';

@Injectable({
  providedIn: 'root',
})
export class StudentGuard {
  constructor(
    private http: HttpClient,
    private credentials: CredentialService
  ) {}

  #loginUrl: string = `${environment.apiUrl}/login/student`;

  canMatch(
    route: Route,
    segments: UrlSegment[]
  ):
    | Observable<boolean | UrlTree>
    | Promise<boolean | UrlTree>
    | boolean
    | UrlTree {
    if (teacherPass()) {
      return false;
    }

    if (studentPass()) {
      this.credentials.token = '18|kE6BRkgq2mylGPy0XNmdnBRkvp0IHHWYrvTIXxHe';
      this.credentials.role = 'student';
      this.credentials.currentUser = {
        id: 1,
        name: 'Dr. Rocky Sporer',
        surnames: 'Grady',
        email: 'q@q',
        password:
          '$2y$10$Fmoz9DiYaf8oZ8jHFDe8re/DYHGdzTJ0O9Yt0HRvYbZmp1/CTfpmO',
        nickname: 'ac4c1b7b-9439-370f-87f4-fcf5eb06be12',
        avatar: 'Eius quis.',
        birth_date: '2023-04-28',
        created_at: '2023-04-28T17:27:40.000000Z',
        updated_at: '2023-04-28T17:27:40.000000Z',
        rankings: [
          {
            id: 6,
            code: '9ccd79f1-67af-3b2d-977a-ea0b71c2e2f9',
            name: 'Corporis et et aliquid quia expedita natus.',
            creator: 1,
            created_at: '2023-04-28T17:27:41.000000Z',
            updated_at: '2023-04-28T17:27:41.000000Z',
            pivot: { student_id: 1, ranking_id: 6, points: 34, kudos: 868 },
          },
          {
            id: 7,
            code: '31cf0a94-2450-33ef-841e-78d80c66c7a6',
            name: 'Ea nostrum eum qui ullam est.',
            creator: 1,
            created_at: '2023-04-28T17:27:41.000000Z',
            updated_at: '2023-04-28T17:27:41.000000Z',
            pivot: { student_id: 1, ranking_id: 7, points: 7, kudos: 642 },
          },
        ],
      } as unknown as IStudent;

      return true;
    }

    if (this.credentials.currentUser) {
      return this.credentials.role === 'student';
    }

    return this.http
      .post<ILoginResponse>(
        this.#loginUrl,
        JSON.stringify({
          email: this.credentials.email,
          password: this.credentials.password,
        }),
        {
          headers: {
            'Content-Type': 'application/json',
          },
          observe: 'response',
        }
      )
      .pipe(
        map((res: HttpResponse<ILoginResponse>): boolean => {
          const token: string = res.body?.token ?? '';
          const role: string = res.body?.role ?? '';
          const user: IStudent | ITeacher | undefined = res.body?.user;

          if (token === '') {
            return false;
          }

          this.credentials.token = token;
          this.credentials.role = role;
          this.credentials.currentUser = user;

          return role === 'student';
        })
      );
  }
}
