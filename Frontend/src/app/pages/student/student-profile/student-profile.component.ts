import {Component, OnInit} from '@angular/core';
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
    private rankingService: RankingService) {
  }

  student: IStudent = this.credentials.currentUser as IStudent;
  rankings: IRanking[] = [];

  ngOnInit(): void {
    this.rankingService.all()
      .subscribe((response: IRanking[]): void => {
        this.rankings = response;
      });
  }
}
