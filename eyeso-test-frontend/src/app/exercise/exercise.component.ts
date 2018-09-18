import { Component, OnInit, Host } from '@angular/core';
import { LocalStorage } from '@ngx-pwa/local-storage';
import { AppComponent } from '../app.component';

const TASK_KEY = '49d07488-3d51-5967-9746-8e3c469e9055';

@Component({
  selector: 'app-exercise',
  templateUrl: './exercise.component.html',
  styleUrls: ['./exercise.component.scss']
})
export class ExerciseComponent implements OnInit {

  tasks = [
    {
      id: "0",
      value: true
    },
    {
      id: "1",
      value: true
    },
    {
      id: "2",
      value: false
    },
    {
      id: "3",
      value: false
    },
    {
      id: "4",
      value: false
    },
    {
      id: "5",
      value: false
    },
    {
      id: "6",
      value: false
    },
    {
      id: "7",
      value: false
    },{
      id: "8",
      value: false
    }
  ]

  constructor(@Host() private _parent: AppComponent, private _localStorage: LocalStorage) { }

  ngOnInit() {
    this._localStorage.getItem(TASK_KEY).subscribe(
      data => {
        if (data !== null) {
          this.tasks = data
          this._parent.totalFinishedTasks = this.tasks.reduce(function(accumulator:any, currentValue:any) {
            console.log(currentValue)
            if (currentValue.value === true) {
              return accumulator + 1;
            }
            return accumulator;
          }, 0)
        }
      }
    )
  }

  taskChanged(index) {
    if (this.tasks[index] !== undefined && this.tasks[index]['value'] !== undefined) {
      this.tasks[index]['value'] === true ? this._parent.totalFinishedTasks++ :this._parent.totalFinishedTasks--
      this._localStorage.removeItem(TASK_KEY).subscribe(() => {})
      this._localStorage.setItem(TASK_KEY, this.tasks).subscribe(() => {})
    }
  }

  get diagnostic() {return JSON.stringify(this.tasks)}
}
