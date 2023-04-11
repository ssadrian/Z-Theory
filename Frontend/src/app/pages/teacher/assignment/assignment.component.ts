import {Component, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {CredentialService} from 'src/app/services/credential.service';
import {AssignmentService} from 'src/app/services/repository/assignment.service';
import {IAssignment} from 'src/models/assignment.model';
import {ITeacher} from 'src/models/teacher.model';
import {IUpdateAssignment} from 'src/models/update/update-assignment';
import {IRanking} from '../../../../models/ranking.model';
import {RankingService} from '../../../services/repository/ranking.service';
import {ConfirmationService, MessageService} from "primeng/api";
import {MiscService} from "../../../services/misc.service";
import {HttpResponse} from "@angular/common/http";
import {forkJoin} from "rxjs";

@Component({
  selector: 'app-assignment',
  templateUrl: './assignment.component.html',
  styleUrls: ['./assignment.component.scss'],
})
export class AssignmentComponent implements OnInit {
  constructor(
    private assignmentService: AssignmentService,
    private rankingService: RankingService,
    private fb: FormBuilder,
    private credentials: CredentialService,
    private confirmationService: ConfirmationService,
    private messageService: MessageService,
    public miscService: MiscService
  ) {
  }

  teacher: ITeacher = this.credentials.currentUser as ITeacher;
  areAssignmentsLoading: boolean = true;

  assignmentForm = this.fb.group({
    title: ['', [Validators.required]],
    description: ['', [Validators.required]],
    content: ['', [Validators.required]],
    points: [0, [Validators.required]],
  });

  assignments: IAssignment[] = [];
  rankings: IRanking[] = [];

  ngOnInit(): void {
    this.areAssignmentsLoading = true;

    forkJoin([
      this.rankingService.createdBy(this.teacher.id),
      this.assignmentService.all()
    ]).subscribe((res: [IRanking[], IAssignment[]]): void => {
      this.rankings = res[0];
      this.assignments = res[1];
      this.areAssignmentsLoading = false;
    });
  }

  updateAssignment(assignment: IAssignment): void {
    const entity: IUpdateAssignment = {
      id: assignment.id,
      title: assignment.title,
      description: assignment.description,
      content: assignment.content,
      points: assignment.points,
      creator: this.teacher.id,
    };

    this.assignmentService.update(assignment.id, entity).subscribe();
  }

  removeFromRanking(ranking: IRanking, assignmentId: number, event: Event): void {
    this.miscService.confirmAction(
      '¿Está seguro de que desea eliminar esta tarea?',
      event.target!,
      (): void => {
        this.assignmentService
          .removeFromRank({
            url_assignmentId: assignmentId,
            url_rankCode: ranking.code
          })
          .subscribe((res: HttpResponse<Object>): void => {
            if (!res.ok) {
              this.messageService.add({
                severity: 'error',
                summary: 'Error',
                detail: 'No se pudo eliminar la tarea del ranking'
              });
              return;
            }

            this.messageService.add({
              severity: 'info',
              summary: 'Tarea Eliminada del Ranking',
            });

            ranking.assignments = ranking.assignments
              .filter((item: IAssignment): boolean => {
                return item.id !== assignmentId;
              });
          });
      }
    );
  }

  deleteAssignment(ranking: IRanking, assignmentId: number, event: Event): void {
    this.miscService.confirmAction(
      '¿Está seguro de que desea borrar esta tarea?',
      event.target!,
      (): void => {
        this.assignmentService
          .delete(assignmentId)
          .subscribe((res: HttpResponse<Object>): void => {
            if (!res.ok) {
              this.messageService.add({
                severity: 'error',
                summary: 'Error',
                detail: 'No se pudo borrar la tarea'
              });
              return;
            }

            this.messageService.add({
              severity: 'info',
              summary: 'Tarea Borrada',
            });

            ranking.assignments = ranking.assignments
              .filter((item: IAssignment): boolean => {
                return item.id !== assignmentId;
              });
          });
      });
  }
}
