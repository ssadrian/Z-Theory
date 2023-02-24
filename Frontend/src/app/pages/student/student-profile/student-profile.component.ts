import {Component, OnInit} from '@angular/core';
import {IRanking} from '../../../../models/ranking.model';
import {CredentialService} from '../../../services/credential.service';
import {IStudent} from '../../../../models/student.model';
import {RankingService} from '../../../services/repository/ranking.service';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss'],
})
export class StudentProfileComponent implements OnInit {
  constructor(
    private credentials: CredentialService,
    private rankingRepository: RankingService) {
  }

  student: IStudent = this.credentials.currentUser as IStudent;
  rankings: IRanking[] = [];

  ngOnInit(): void {
    this.rankingRepository.all()
      .subscribe((rankings: IRanking[]): void => {
        this.rankings = rankings.filter((r: IRanking): boolean => r.student_id === this.student.id);
      });
  }
}
