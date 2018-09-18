import { Component, OnInit, ViewContainerRef } from '@angular/core';
import { ApiService } from '../api.service';
import { Employee } from '../employee';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

const genders = [
  {
    value: "male",
    label: "Male"
  },
  {
    value: "female",
    label: "Female"
  }
]

@Component({
  selector: 'app-employee-edit',
  templateUrl: './employee-edit.component.html',
  styleUrls: ['./employee-edit.component.scss']
})
export class EmployeeEditComponent implements OnInit {

  employee = null

  genders = [
    {
      value: "male",
      display: "Male"
    },
    {
      value: "female",
      display: "Female"
    }
  ]

  jobs = null

  formErrors = null

  constructor(private _apiService: ApiService, private _router: Router, private _route: ActivatedRoute, public toastr: ToastsManager, vcr: ViewContainerRef) {
    this.toastr.setRootViewContainerRef(vcr);
 }

  ngOnInit() {
    var employeeId = this._route.snapshot.params.id

    this._apiService.getEmployee(employeeId)
      .subscribe(
        data => {
          var name = data.hasOwnProperty('name') ? data['name'] : null
          var age = data.hasOwnProperty('age') ? data['age'] : null
          var gender = data.hasOwnProperty('gender') ? data['gender'] : null
          var jobId = data.hasOwnProperty('job') && data['job'].hasOwnProperty('id') ? data['job']['id'] : null

          this.employee = new Employee(employeeId, name, age, gender, jobId)
        }
      )

      this.getJobs()
  }

  getJobs() {
    this._apiService.getJobs().subscribe(
      data => {
        this.jobs = data
      },
      err => console.error(err),
      () => {
        console.log('Jobs loaded successfully')
      }
    )
  }

  updateEmployee() {
    this.formErrors = null
    this._apiService.updateEmployee(this.employee).subscribe(
      data => {
        this.toastr.success('Employee updated successfully')
        this._router.navigate(['/employees'])
        // console.log(data)
      },
      err => {
        this.formErrors = err
        // console.log(err)
      }
    )
  }

  propertyFormErrorExist(propName: string) {
    return this.formErrors !== null
      && this.formErrors['error']['errors'][propName] !== undefined
  }

  propertyFormErrorGetMessages(propName: string) {
    if (this.propertyFormErrorExist(propName)) {
      return this.formErrors['error']['errors'][propName]['messages']
    }
  }
}
