
class Component {
    element: any;
    name: string;

    constructor(element: any, name?: string) {
      this.element = element;
      this.name = name;
    }

    greet() {
        return "Hello, " + this.element;
    }
}

class SelectOption {
  text: string;
  value: any;

  constructur(value: any, text: string) {
    this.value = value;
    this.text = text;
  }
}

interface Valueable {
  value: any;

  setValue(value:any);

  getValue(value:any);
}

class SelectOne extends Component implements Valueable {
  value : any;
  options : [SelectOption];

  constructor(element: any, options?: [SelectOption]) {
    super({element:element});
  }
  
  setValue(value:any) {
    this.value = value;
  }
  
  getValue() {
    return this.value;
  }
}

var node = document.createElement('div');

var s = new SelectOne({element:node});
s.setValue(5);

