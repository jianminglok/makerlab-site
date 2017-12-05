/**
 * The file is admin feeds pay-control manage page.
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types'
import Grid from 'material-ui/Grid';
import Card, { CardHeader, CardContent, CardMedia, CardActions } from 'material-ui/Card';
import Typography from 'material-ui/Typography';
import Dialog, { DialogContent, DialogActions } from 'material-ui/Dialog';
import Snackbar from 'material-ui/Snackbar';
import Avatar from 'material-ui/Avatar';
import Button from 'material-ui/Button';
import IconButton from 'material-ui/IconButton';
import { FormControlLabel } from 'material-ui/Form';
import Switch from 'material-ui/Switch';
import CircularProgress from 'material-ui/Progress/CircularProgress';

import withStyles from 'material-ui/styles/withStyles';
import request, { createRequestURI } from '../utils/request';

const styles = (theme:object) => ({
  root: {
    width: '100%',
    padding: theme.spacing.unit,
    margin: 0
  }
});

class PayControl extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired,
  };

  state = {
    open: false,
    close: {
      open: false,
      ing: false
    }
  };

  render () {
    let { open = false, close } = this.state;
    const { classes } = this.props;

    return (
      <div>
        <Grid container className={classes.root}>
          <Grid item xs={12} sm={12}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态付费控制
                </Typography>
                <Typography component="p">
                  用于控制客户端发送动态是否可以设置付费
                </Typography>
              </CardContent>

              <CardActions>

                <FormControlLabel
                  control={
                    <Switch
                      checked={open}
                      onChange={ (event, checked) => !checked ? this.handleSetFalse(checked) : this.handleStatusChange (checked) }
                    />
                  }
                  label={open ? '已开启' : '已关闭'}
                />

              </CardActions>

            </Card>
          </Grid>
        </Grid>
        <Dialog open={!! close.open}>
          <DialogContent>确定要关闭收费吗吗？</DialogContent>
          <DialogActions>
            { close.ing
              ? <Button disabled>取消</Button>
              : <Button onTouchTap={() => this.handleCannel()}>取消</Button>
            }
            { close.ing
              ? <Button disabled><CircularProgress size={14} /></Button>
              : <Button color="primary" onTouchTap={() => this.handleStatusChange()}>确定</Button>
            }
          </DialogActions>
        </Dialog>
      </div>
    )
  }

  handleSetFalse (checked) {
    this.setState({
      ...this.state,
      close: {
        open: true,
        ing: false
      }
    })
  }

  handleStatusChange () {
    open = !this.state.open;
    if (!open) {
      this.setState({
        ...this.state,
        close: {
          open: true,
          ing: true
        }
      })
    }
    request.patch(createRequestURI('paycontrol'), {
      open: open
    }, {
      validataStatus: status => status === 201
    }).then( () => {
      this.handleCannel();
      this.setState({
        ...this.state,
        open: open
      });
    }).catch ( error => {

    })
  }

  handleCannel () {
    this.setState({
      close: {
        open: false,
        ing: false
      }
    })
  }

  componentDidMount () {
    request.get(createRequestURI('paycontrol'), {
      validataStatus: status => status === 200
    }).then(({ data = {} }) => {
      this.setState({
        open: data.open
      })
    }).catch( error => {
      alert('获取配置信息失败');
    })
  }
}

export default withStyles(styles)(PayControl);