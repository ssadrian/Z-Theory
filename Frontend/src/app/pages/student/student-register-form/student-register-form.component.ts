import {Component} from "@angular/core";
import {AbstractControl, FormBuilder, Validators} from "@angular/forms";
import {Router} from "@angular/router";
import {IFormStudent} from "src/models/form/form-student";
import {Base64Service} from "../../../services/base64.service";
import {RegistrationService} from "../../../services/registration.service";

@Component({
  selector: "app-student-register-form",
  templateUrl: "./student-register-form.component.html",
  styleUrls: ["./student-register-form.component.scss"],
})
export class StudentRegisterFormComponent {
  constructor(
    private register: RegistrationService,
    private fb: FormBuilder,
    private router: Router,
    public b64: Base64Service) {
  }

  isSubmit: boolean = false;

  studentForm = this.fb.group({
    nickname: ["", [Validators.required]],
    birth_date: [<Date | null>null, [Validators.required]],
    name: ["", [Validators.required]],
    surnames: ["", [Validators.required]],
    password: ["", [Validators.required]],
    password_confirmation: ["", [Validators.required]],
    email: ["", [Validators.required, Validators.email]],
    avatar: [""],
    tos: [false, [Validators.requiredTrue]],
  });

  b64Avatar: string = "";

  get formControl(): { [key: string]: AbstractControl } {
    return this.studentForm.controls;
  }

  encodeAvatar(event: Event): void {
    this.b64.toBase64(event)
      .then((b64: string): void => {
        this.b64Avatar = b64;
      });
  }

  submit(): void {
    this.isSubmit = true;

    if (!this.studentForm.valid) {
      return;
    }

    let formValue = this.studentForm.value;
    let student: IFormStudent = {
      avatar: this.b64Avatar,
      name: formValue.name!,
      surnames: formValue.surnames!,
      nickname: formValue.nickname!,
      email: formValue.email!,
      password: formValue.password!,
      password_confirmation: formValue.password_confirmation!,
      birth_date: formValue.birth_date!,
    };

    this.register.registerStudent(student)
      .subscribe(response => {
        if (response.ok) {
          this.router.navigate(["/login"]);
        }
      });
  }
}
