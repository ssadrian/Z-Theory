import {Component, OnInit} from '@angular/core';
import {CredentialService} from 'src/app/services/credential.service';
import {IRanking} from '../../../../models/ranking.model';
import {RankingService} from "../../../services/repository/ranking.service";
import {ITeacher} from "../../../../models/teacher.model";
import {MiscService} from "../../../services/misc.service";
import {IDeclineStudentAssignation} from "../../../../models/update/decline-student-assignation";
import {IAcceptStudentAssignation} from "../../../../models/update/accept-student-assignation";
import {MessageService} from "primeng/api";
import {IStudent} from "../../../../models/student.model";
import {JoinStatus} from "../../../../models/misc/join-status";

@Component({
  selector: 'app-queues-list',
  templateUrl: './queues-list.component.html',
  styleUrls: ['./queues-list.component.scss']
})
export class QueuesListComponent implements OnInit {
  constructor(
    private rankingService: RankingService,
    private credentialService: CredentialService,
    private messageService: MessageService,
    private miscService: MiscService
  ) {
  }

  user: ITeacher = this.credentialService.currentUser as ITeacher;
  rankings: IRanking[] = [];

  ngOnInit(): void {
    this.rankingService
      .queuesForTeacher(this.user.id)
      .subscribe((res: IRanking[]): void => {
        this.rankings = res;
      });
  }

  acceptStudent(code: string, student: IStudent): void {
    const req: IAcceptStudentAssignation = {
      url_studentId: student.id,
      code: code
    };

    this.rankingService
      .acceptStudent(req)
      .subscribe((): void => {
        student.pivot.join_status_id = JoinStatus.Accepted;

        this.messageService.add({
          key: 'toasts',
          severity: 'success',
          summary: 'Estudiante Acceptado!'
        });
      });
  }

  declineStudent(ranking: IRanking, student: IStudent, $event: MouseEvent): void {
    this.miscService
      .confirmAction(
        '¿Desea rechazar la petición de este estudiante?',
        $event.target!,
        (): void => {
          const req: IDeclineStudentAssignation = {
            code: ranking.code,
            url_studentId: student.id
          };

          this.rankingService
            .declineStudent(req)
            .subscribe((): void => {
              this.messageService.add({
                key: 'toasts',
                severity: 'success',
                summary: 'Petición rechazada!'
              });

              student.pivot.join_status_id = JoinStatus.Declined;
            });
        }
      );
  }

  isPending(joinStatus: number): boolean {
    return joinStatus === JoinStatus.Pending;
  }
}
