import React from 'react';
import ReactDOM from 'react-dom/client';
import Moment from 'moment';
import './index.css';

// Classes should be split out.
class Entry extends React.Component {
  render() {
    return (
      <li>
        <h2>{this.props.value.attributes.title}</h2>
        <span>{Moment(this.props.value.attributes.created).format('DD/MM/YYYY')}</span>
      </li>
    );
  }
}

class Entries extends React.Component {
  constructor(props) {
    super(props);
    this.prevEntries = this.prevEntries.bind(this);
    this.nextEntries = this.nextEntries.bind(this);
    this.state = {
      error: null,
      isLoaded: false,
      items: [],
      links: []
    };
  }

  renderEntry(i) {
    return <Entry value={i} />;
  }

  // Fetch should be extracted to a separate function.
  prevEntries() {
    fetch(this.state.links.prev.href)
      .then(res => res.json())
      .then(
        (result) => {
          this.setState({
            isLoaded: true,
            items: result.data,
            links: result.links
          });
        },
        (error) => {
          this.setState({
            isLoaded: true,
            error
          });
        }
      )
  }

  nextEntries() {
    fetch(this.state.links.next.href)
      .then(res => res.json())
      .then(
        (result) => {
          this.setState({
            isLoaded: true,
            items: result.data,
            links: result.links
          });
        },
        (error) => {
          this.setState({
            isLoaded: true,
            error
          });
        }
      )
  }

  componentDidMount() {
    fetch("http://sciensano-test.local/jsonapi/hd_entry/hd_entry?page[limit]=3&sort=-created")
      .then(res => res.json())
      .then(
        (result) => {
          this.setState({
            isLoaded: true,
            items: result.data,
            links: result.links
          });
        },
        (error) => {
          this.setState({
            isLoaded: true,
            error
          });
        }
      )
  }

  render() {
    const { error, isLoaded, items } = this.state;
    if (error) {
      return <div>Error: {error.message}</div>;
    }
    else if (!isLoaded) {
      return <div>Loading...</div>;
    }
    else {
      return (
        <div>
        <ul>
          {items.map(item => {
            return (
              this.renderEntry(item)
            )
          })}
        </ul>

        {this.state.links.prev !== undefined &&
          <button onClick={this.prevEntries}>Vorige</button>
        }

        {this.state.links.next !== undefined &&
          <button onClick={this.nextEntries}>Volgende</button>
        }
        </div>
      );
    }
  }
}

const root = ReactDOM.createRoot(document.getElementById('block-entryreactblock'));
root.render(<Entries />);
