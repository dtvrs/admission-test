import { Component, OnInit } from '@angular/core';
import {ApiService} from '../api.service';
import { Employee } from '../employee';
import { Router } from '@angular/router';

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
  selector: 'app-employee-create',
  templateUrl: './employee-create.component.html',
  styleUrls: ['./employee-create.component.scss']
})


export class EmployeeCreateComponent implements OnInit {
  employee = new Employee(null, null, null, null, null)
  
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
  
  constructor(private _apiService: ApiService, private _router: Router) { }

  ngOnInit() {
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

  createEmployee() {
    this.formErrors = null
    this._apiService.createEmployee(this.employee).subscribe(
      data => {
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

   // TODO: Remove this when we're done
   get diagnostic() { return JSON.stringify(this.employee); }
}
