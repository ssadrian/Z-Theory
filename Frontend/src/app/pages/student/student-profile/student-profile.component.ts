import { Component, OnInit } from '@angular/core';
import { Table } from 'primeng/table';
import { IRanking } from '../../../../models/ranking.model';
import { IStudent } from '../../../../models/student.model';
import { CredentialService } from '../../../services/credential.service';
import { RankingService } from '../../../services/repository/ranking.service';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss'],
})
export class StudentProfileComponent implements OnInit {
  constructor(
    private credentials: CredentialService,
    private rankingService: RankingService
  ) {}

  customers = [
    { name: 'Amy Elsner', image: 'amyelsner.png' },
    { name: 'Anna Fali', image: 'annafali.png' },
    { name: 'Asiya Javayant', image: 'asiyajavayant.png' },
    { name: 'Bernardo Dominic', image: 'bernardodominic.png' },
    { name: 'Elwin Sharvill', image: 'elwinsharvill.png' },
    { name: 'Ioni Bowcher', image: 'ionibowcher.png' },
    { name: 'Ivan Magalhaes', image: 'ivanmagalhaes.png' },
    { name: 'Onyama Limba', image: 'onyamalimba.png' },
    { name: 'Stephen Shaw', image: 'stephenshaw.png' },
    { name: 'Xuxue Feng', image: 'xuxuefeng.png' },
  ];

  representatives = [
    { label: 'Unqualified', value: 'unqualified' },
    { label: 'Qualified', value: 'qualified' },
    { label: 'New', value: 'new' },
    { label: 'Negotiation', value: 'negotiation' },
    { label: 'Renewal', value: 'renewal' },
    { label: 'Proposal', value: 'proposal' },
  ];

  statuses = [
    { label: 'Unqualified', value: 'unqualified' },
    { label: 'Qualified', value: 'qualified' },
    { label: 'New', value: 'new' },
    { label: 'Negotiation', value: 'negotiation' },
    { label: 'Renewal', value: 'renewal' },
    { label: 'Proposal', value: 'proposal' },
  ];

  loading: boolean = true;

  activityValues: number[] = [0, 100];
  student: IStudent = this.credentials.currentUser as IStudent;
  rankings: IRanking[] = [];

  ngOnInit(): void {
    this.rankingService.all().subscribe((response: IRanking[]): void => {
      this.rankings = response;
    });

  
  }

  clear(table: Table) {
    table.clear();
  }
}
