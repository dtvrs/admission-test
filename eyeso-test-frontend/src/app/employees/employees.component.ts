import { Component, OnInit, ViewContainerRef } from '@angular/core';
import {ApiService } from '../api.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { Router } from '@angular/router';


@Component({
  selector: 'app-employees',
  templateUrl: './employees.component.html',
  styleUrls: ['./employees.component.scss']
})

export class EmployeesComponent implements OnInit {
  public employees;
  loading = true;


  constructor(private _apiService: ApiService, public toastr: ToastsManager, vcr: ViewContainerRef) {
    this.toastr.setRootViewContainerRef(vcr);
 }

  ngOnInit() {
      this.getEmployees();
  }

  getEmployees() {
      this._apiService.getEmployees().subscribe(
          data => { this.employees = data; },
          err => console.error(err),
          () => { this.loading = false; console.log('Employees loaded successfully'); }
      );
  }
  
  deleteEmployee(employeeId: number) {
    this._apiService.deleteEmployee(employeeId)
        .subscribe(
            data => {
                this.toastr.success('Employee deleted successfully')
                this.loading = true
                this.getEmployees()
                // console.log(data)
            },
            err => {
                this.toastr.error(err.error.error);
                // console.log(err)
            }
        )
  }
}
