import { Component, Input } from '@angular/core';
import { LocalStorage } from '@ngx-pwa/local-storage';

const TASK_KEY = '49d07488-3d51-5967-9746-8e3c469e9055';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  public navBarCollapsed = false
  
  @Input() public totalFinishedTasks:number = 0;
}
