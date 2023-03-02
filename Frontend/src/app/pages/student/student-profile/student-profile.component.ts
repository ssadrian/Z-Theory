import {Component, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {Observable, Subscription} from 'rxjs';
import {ICreateStudentAssignation} from '../../../../models/create/create-student-assignation';
import {IRanking} from '../../../../models/ranking.model';
import {IStudent} from '../../../../models/student.model';
import {CredentialService} from '../../../services/credential.service';
import {RankingService} from '../../../services/repository/ranking.service';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss'],
})
export class StudentProfileComponent implements OnInit {
  constructor(
    private credentials: CredentialService,
    private rankingService: RankingService,
    private fb: FormBuilder) {
  }

  codeForm = this.fb.group({
    code: ['', [Validators.required]],
  });

  student: IStudent = this.credentials.currentUser as IStudent;
  rankings: IRanking[] = [];

  #updateRanks(): void {
    this.rankingService
      .leaderboardsForStudent(this.student.id)
      .subscribe((rankings: IRanking[]): void => {
        this.rankings = rankings;

        this.rankings.forEach((rank: IRanking): void => {
          rank.students.sort((a: IStudent, b: IStudent) => {
            return a.nickname.localeCompare(b.nickname)
              || b.pivot.points - a.pivot.points;
          });
        });
      });
  };

  ngOnInit(): void {
    this.#updateRanks();
  }

  joinRanking(): void {
    const code: string = this.codeForm.value.code ?? '';

    if (!this.#isValidUuid(code)) {
      return;
    }

    const entity: ICreateStudentAssignation = {
      code: code,
      student_id: this.student.id,
    };

    this.rankingService.assignStudent(entity)
      .subscribe(response => {
        this.#updateRanks();
        this.codeForm.reset()
      });
  }

  #isValidUuid(uuid: string): boolean {
    const uuidRegex: RegExp = /^[0-9A-Za-z]{8}-[0-9A-Za-z]{4}-4[0-9A-Za-z]{3}-[89ABab][0-9A-Za-z]{3}-[0-9A-Za-z]{12}$/g;
    return uuidRegex.test(uuid);
  }
}
