import {Component, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {MenuItem, MessageService} from 'primeng/api';
import {Base64Service} from 'src/app/services/base64.service';
import {AssignmentService} from 'src/app/services/repository/assignment.service';
import {RankingService} from 'src/app/services/repository/ranking.service';
import {StudentService} from 'src/app/services/repository/student.service';
import {IAssignment} from 'src/models/assignment.model';
import {IStudent} from 'src/models/student.model';
import {IUpdateRanking} from 'src/models/update/update-ranking';
import {IUpdateRankingStudent} from 'src/models/update/update-ranking-student';
import {IUpdateTeacher} from 'src/models/update/update-teacher';
import {IUser} from 'src/models/user.model';
import {v4 as uuidv4} from 'uuid';
import {IRanking} from '../../../../models/ranking.model';
import {ITeacher} from '../../../../models/teacher.model';
import {IUpdatePassword} from '../../../../models/update/update-password';
import {CredentialService} from '../../../services/credential.service';
import {TeacherService} from '../../../services/repository/teacher.service';
import {MiscService} from "../../../services/misc.service";
import {HttpErrorResponse, HttpResponse} from "@angular/common/http";
import {catchError, throwError} from "rxjs";

@Component({
  selector: 'app-teacher-profile',
  templateUrl: './teacher-profile.component.html',
  styleUrls: ['./teacher-profile.component.scss'],
})
export class TeacherProfileComponent implements OnInit {
  constructor(
    private teacherService: TeacherService,
    private credentials: CredentialService,
    private fb: FormBuilder,
    private rankingService: RankingService,
    private messageService: MessageService,
    private b64: Base64Service,
    private studentService: StudentService,
    private assignmentService: AssignmentService,
    public miscService: MiscService
  ) {
  }

  inputEnabled: boolean = false;
  show: boolean = false;
  showAssignment: boolean = false;

  teacher: ITeacher = this.credentials.currentUser as ITeacher;
  createdRankings: IRanking[] = [];
  createdAssignments: IAssignment[] = [];
  isSubmit: boolean = false;
  isSidebarVisible: boolean = false;
  showPasswordChangeDialog: boolean = false;
  showRankingUpdateDialog: boolean = false;

  courses: any[] = [
    {name: 'DAW', totalStudents: 10},
    {name: 'DAM', totalStudents: 15},
    {name: 'SMIX', totalStudents: 5},
  ];

  createRankingForm = this.fb.group({
    name: ['', [Validators.required]],
  });

  passwordForm = this.fb.group({
    password: ['', [Validators.required]],
    new_password: ['', [Validators.required]],
  });

  assignmentForm = this.fb.group({
    title: ['', [Validators.required]],
    description: ['', [Validators.required]],
    content: ['', [Validators.required]],
    points: ['', [Validators.required]],
  });

  rankingButtons: MenuItem[] = [];

  selectedRanking?: IRanking;

  setSelectedRanking(ranking: IRanking): void {
    this.selectedRanking = ranking;
  }

  #b64Avatar: string = '';

  ngOnInit(): void {
    this.rankingButtons = [
      {
        label: 'Añadir entrega',
        icon: 'pi pi-plus-circle',
        command: (): void => {
          this.showAssignmentForm();
        }
      },
      {
        label: 'Modificar',
        icon: 'pi pi-pencil',
        command: () => {
          if (!this.selectedRanking) {
            return;
          }


        }
      },
      {
        label: 'Refrescar codigo',
        icon: 'pi pi-undo',
        command: (): void => {
          if (!this.selectedRanking) {
            return;
          }

          this.changeRankingId(this.selectedRanking);
        }
      },
      {
        label: 'Eliminar',
        icon: 'pi pi-minus-circle',
        styleClass: 'bg-red-600',
        command: (): void => {
          if (!this.selectedRanking) {
            return;
          }

          this.rankingService
            .delete(this.selectedRanking.code)
            .subscribe(() => {
              this.createdRankings = this.createdRankings.filter(x => x.code !== this.selectedRanking?.code);

              this.messageService.add({
                key: 'toasts',
                severity: 'success',
                detail: 'Ranking eliminado!'
              });
            });
        }
      }
    ];

    this.#updateCreatedRanks();

    this.assignmentService
      .createdByTeacher(this.teacher.id)
      .subscribe((response: IAssignment[]): void => {
        this.createdAssignments = response;
      });
  }

  createRankingSubmit(): void {
    this.isSubmit = true;

    const formValue = this.createRankingForm.value;
    this.rankingService
      .create({
        code: uuidv4(),
        creator: this.teacher.id,
        name: formValue.name!,
      })
      .subscribe(() => {
        this.messageService.add({
          key: 'toasts',
          severity: 'success',
          detail: 'Ranking creado!'
        });
        this.#updateCreatedRanks();
      });
  }

  #updateCreatedRanks(): void {
    this.rankingService
      .createdBy(this.teacher.id)
      .subscribe((rankings: IRanking[]): void => {
        this.createdRankings = rankings;

        this.createdRankings.forEach((rank: IRanking): void => {
          rank.students.sort((a: IStudent, b: IStudent) => {
            return (
              b.pivot.points - a.pivot.points ||
              a.nickname.localeCompare(b.nickname)
            );
          });
        });
      });
  }

  updateRanking() {
    if (!this.selectedRanking) {
      return;
    }

    const entity: IUpdateRanking = {
      url_oldCode: this.selectedRanking.code,
      name: this.selectedRanking.name
    };
  }

  encodeAvatar(event: any): void {
    this.b64.toBase64(event).then((b64: string): void => {
      this.#b64Avatar = b64;
      this.updateAvatar();
    });

    this.toggle();
  }

  updateAvatar(): void {
    const entity: IUpdateTeacher = {
      avatar: this.#b64Avatar,
      name: this.teacher.name!,
      surnames: this.teacher.surnames!,
      nickname: this.teacher.nickname!,
      center: this.teacher.center!,
    };

    this.teacherService
      .update(this.teacher.id, entity)
      .subscribe(() => {});
    this.teacher.avatar = this.#b64Avatar;

    this.show = false;
  }

  toggle(): void {
    this.show = !this.show;
  }

  changeRankingId(ranking: IRanking): void {
    const newRankingCode: string = uuidv4();
    const entity: IUpdateRanking = {
      url_oldCode: ranking.code,
      code: newRankingCode,
      name: ranking.name,
      creator: ranking.creator,
    };

    this.rankingService.update(entity)
      .subscribe((response: HttpResponse<Object>): void => {
        if (!response.ok || !this.selectedRanking) {
          return;
        }

        this.selectedRanking.code = newRankingCode;
      });
  }

  deleteStudent(student: IUser, event: Event): void {
    this.miscService.confirmAction(
      '¿Estás seguro de que deseas eliminar este estudiante?',
      event.target!,
      (): void => {
        this.studentService
          .delete(student.id)
          .subscribe();
      }
    );
  }

  modifyStudentPoints(ranking: IRanking, student: IStudent): void {
    this.inputEnabled = false;

    const entity: IUpdateRankingStudent = {
      url_studentId: student.id,
      url_rankingCode: ranking.code,
      points: student.pivot.points,
    };
    this.rankingService.updateForStudent(entity).subscribe();
  }

  showAssignmentForm(): void {
    this.isSidebarVisible = true;
    this.showAssignment = true;
  }

  createAssignment(): void {
    const formValue = this.assignmentForm.value;

    this.assignmentService
      .create({
        title: formValue.title!,
        description: formValue.description!,
        content: formValue.content!,
        points: formValue.points,
        creator: this.teacher.id,
      })
      .subscribe(() => {
      });

    this.assignmentService
      .createdByTeacher(this.teacher.id)
      .subscribe((res: IAssignment[]): void => {
        for (let assignment of res) {
          if (assignment.title !== formValue.title) {
            continue;
          }

          this.createdAssignments.push(assignment);

          // Create assignment-ranking relation
          this.assignmentService.assignToRank({
            id: assignment.id,
            url_rankCode: this.selectedRanking?.code!
          }).subscribe(() => {
            this.messageService.add({
              key: 'toasts',
              severity: 'success',
              detail: 'Tarea asignada.'
            })
          });
        }
      });

    this.showAssignment = false;
  }

  showPasswordChangeForm(): void {
    this.showPasswordChangeDialog = true;
  }

  changePassword(): void {
    const formValues = this.passwordForm.value;
    const entity: IUpdatePassword = {
      id: this.credentials.currentUser?.id!,
      password: formValues.password!,
      new_password: formValues.new_password!,
    };

    this.teacherService
      .updatePassword(entity)
      .pipe(
        catchError((err: HttpErrorResponse) => {
          if (!err.ok) {
            this.messageService.add({
              key: 'toasts',
              severity: 'error',
              summary: 'No se pudo cambiar la contraseña'
            });
          }

          return throwError(() => new Error('Ignore the error'));
        })
      )
      .subscribe((): void => {
        this.passwordForm.reset();

        this.messageService.add({
          key: 'toasts',
          severity: 'success',
          summary: 'Contraseña cambiada!'
        });
        this.onShowPasswordDialogReject();
      });
  }

  onShowPasswordDialogReject(): void {
    this.showPasswordChangeDialog = false;
  }
}
