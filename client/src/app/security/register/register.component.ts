import {Component, Inject} from '@angular/core';
import {Validators} from "@angular/forms";
import {Register, REGISTER, RegisterInput} from "./register.service";
import {ControlsOf, FormControl, FormGroup} from "@ngneat/reactive-forms";
import {HttpErrorResponse} from "@angular/common/http";

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent {
  registerForm = new FormGroup<ControlsOf<RegisterInput>>({
    email: new FormControl('', [
      Validators.required,
      Validators.email
    ]),
    plainPassword: new FormControl('', [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)
    ])
  })

  constructor(@Inject(REGISTER) private register: Register) {
  }

  onSubmit() {
    this.register.execute(this.registerForm.value).subscribe({
      next: registerOutput => {
        console.log('REGISTER', registerOutput)
      },
      error: (err: HttpErrorResponse) => {
        this.registerForm.mergeErrors({violations: err.error.violations});
      }
    });

    return false;
  }
}