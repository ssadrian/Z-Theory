import { Component } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { CredentialService } from 'src/app/services/credential.service';
import { AssignmentService } from 'src/app/services/repository/assignment.service';
import { IAssignment } from 'src/models/assignment.model';
import { ITeacher } from 'src/models/teacher.model';
import { IUpdateAssignment } from 'src/models/update/update-assignment';

@Component({
  selector: 'app-assignment',
  templateUrl: './assignment.component.html',
  styleUrls: ['./assignment.component.scss'],
})
export class AssignmentComponent {
  assignment!: IAssignment;

  constructor(
    private assignmentService: AssignmentService,
    private fb: FormBuilder,
    private credentials: CredentialService
  ) {}

  teacher: ITeacher = this.credentials.currentUser as ITeacher;

  assignmentForm = this.fb.group({
    titleAssignment: ['', [Validators.required]],
    descriptionAssignment: ['', [Validators.required]],
    contentAssignment: ['', [Validators.required]],
    pointsAssignment: [0, [Validators.required]],
  });

  ngOnInit(): void {
    this.assignmentService.all().subscribe((data) => {
      this.assignment = data[0]; // aquí asumimos que el primer registro de la lista es el que queremos mostrar
    });
  }

  deleteAssignment(assignment: IAssignment): void {
    if (window.confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
      this.assignmentService.delete(assignment.id).subscribe();
    }
  }

  updateAssignment(assignment: IAssignment) {
    const formValue = this.assignmentForm.value;
    const entity: IUpdateAssignment = {
      id: assignment.id,
      title: formValue.titleAssignment!,
      description: formValue.descriptionAssignment!,
      content: formValue.contentAssignment!,
      points: formValue.pointsAssignment,
      creator: this.teacher.id,
    };

    this.assignmentService.update(assignment.id, entity).subscribe();
  }
}
