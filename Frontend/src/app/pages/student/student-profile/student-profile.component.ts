import { Component, OnInit } from '@angular/core';
import { Table } from 'primeng/table';
import { IRanking } from '../../../../models/ranking.model';
import { IStudent } from '../../../../models/student.model';
import { CredentialService } from '../../../services/credential.service';
import { RankingService } from '../../../services/repository/ranking.service';
import { FormBuilder, Validators } from '@angular/forms';
import { ICreateStudentAssignation } from '../../../../models/create/create-student-assignation';
@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss'],
})
export class StudentProfileComponent implements OnInit {
  constructor(
    private credentials: CredentialService,
    private rankingService: RankingService,
    private fb: FormBuilder
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
            return (
              a.nickname.localeCompare(b.nickname) ||
              b.pivot.points - a.pivot.points
            );
          });
        });
      });
  }

  ngOnInit(): void {
    this.rankingService.all().subscribe((response: IRanking[]): void => {
      this.rankings = response;
    });
  }

  clear(table: Table) {
    table.clear();
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

    this.rankingService.assignStudent(entity).subscribe((response) => {
      this.#updateRanks();
      this.codeForm.reset();
    });
  }

  #isValidUuid(uuid: string): boolean {
    const uuidRegex: RegExp =
      /^[0-9A-Za-z]{8}-[0-9A-Za-z]{4}-4[0-9A-Za-z]{3}-[89ABab][0-9A-Za-z]{3}-[0-9A-Za-z]{12}$/g;
    return uuidRegex.test(uuid);
  }
}
