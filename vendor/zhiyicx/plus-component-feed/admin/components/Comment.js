/**
 * The file is admin feeds comments manage page.
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types'

import withStyles from 'material-ui/styles/withStyles';
import Grid from 'material-ui/Grid';
import Card, { CardHeader, CardContent, CardMedia, CardActions } from 'material-ui/Card';
import Typography from 'material-ui/Typography';
import Dialog, { DialogContent, DialogActions } from 'material-ui/Dialog';
import Snackbar from 'material-ui/Snackbar';
import Avatar from 'material-ui/Avatar';
import Button from 'material-ui/Button';
import IconButton from 'material-ui/IconButton';
import CircularProgress from 'material-ui/Progress/CircularProgress';
import Drawer from 'material-ui/Drawer';
import Chip from 'material-ui/Chip';

import FavoriteIcon from 'material-ui-icons/Favorite';
import Forum from 'material-ui-icons/Forum';
import Delete from 'material-ui-icons/Delete';
import CloseIcon from 'material-ui-icons/Close';
import _ from 'lodash';

import request, { createRequestURI } from '../utils/request';

const styles = (theme:object) => ({
  root: {
    padding: theme.spacing.unit,
    margin: 0,
    width: '100%'
  },
  loadMoreBtn: {
    margin: theme.spacing.unit,
    width: `calc(100% - ${theme.spacing.unit * 2 }px)`
  },
  progress: {
    margin: `0 ${theme.spacing.unit}px`,
  },
  progeessHide: {
    margin: `0 ${theme.spacing.unit}px`,
    visibility: 'hidden'
  }
});

class Comment extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired,
  };

  state = {
    comments: [],
    del: {
      comment: null,
      ing: false,
    },
    snackbar: {
      open: false,
      message: '',
      vertical: 'bottom',
      horizontal: 'right',
    },
    drawer: null,
    loadMoreBtnText: '加载更多',
    loadMoreBtnDisabled: false,
    loading: false,
    nextPage: null
  };

  // 删除评论
  handleDelete () {
    const { del: { comment = null } } = this.state;

    this.setState({
      del: {comment: comment, ing: true}
    });

    request.delete(
      createRequestURI(`comments/${comment}`), {
        validateStatus: status => status === 204
      }
    ).then(() => {
      let { comments = [] } = this.state;
      const index = _.findIndex(comments, (comment) => {
        return comment === comment.id;
      });
      comments.splice(index, 1);

      this.setState({
        ...this.state,
        comments: comments,
        del: { comment: null, ing: false }
      })
    }).catch( error => {
      console.log(error);
    });
  };

  handlePushDelete (id) {
    this.setState({
      ...this.state,
      del: { comment: id, ing: false }
    })
  };

  handlePushClose () {
    this.setState({
      ...this.state,
      del: { comment: null, ing: false }
    });
  };

  handleLoadMoreComments () {
    const { nextPage = null } = this.state;
    if (!nextPage) {
      return false;
    }

    this.setState({
      loading: true,
      loadMoreBtnText: '',
      loadMoreBtnDisabled: true
    });
    request.get(
      createRequestURI('comments') ,{
        params: {
          page: nextPage
        }
      },
      {
        validateStatus: status => status === 200
      }
    ).then(({ data = [] }) => {
      let comments = this.state.comments;
      let loadMoreBtnText, loadMoreBtnDisabled, loading = false;
      if (!data.nextPage) {
        loadMoreBtnText = '已全部加载  ';
        loadMoreBtnDisabled = true;
      } else {
        loadMoreBtnText = '加载更多';
        loadMoreBtnDisabled = false;
      }
      this.setState({
        comments: [...comments, ...data.comments],
        loading,
        loadMoreBtnDisabled,
        loadMoreBtnText,
        nextPage: data.nextPage
      })
    }).catch( error => {

    });
  };

  render () {
    const { classes } = this.props;
    const { comments = [], del, snackbar, drawer } = this.state;

    return(
      <div>
        <Grid container className={classes.root}>

          { comments.map(({
            id,
            created_at,
            body: content,
            user: { name, id: user_id } = {}
          }) => (

            <Grid item xs={12} sm={6} key={id}>
              <Card>

                <CardHeader
                  avatar={<Avatar>{name[0]}</Avatar>}
                  title={`${name} (${user_id})`}
                  subheader={created_at}
                />

                <CardContent //onTouchTap={() => this.handleRequestDrawer(id)}
                >
                  <Typography>
                    #{id}
                  </Typography>
                  {content}
                </CardContent>

                <CardActions>

                  <div className={classes.flexGrow} />

                  <IconButton
                    onTouchTap={() => this.handlePushDelete(id)}
                  >
                    <Delete />
                  </IconButton>

                </CardActions>

              </Card>
            </Grid>
          ))}
        </Grid>
        <Button
          raised
          color="primary"
          className={classes.loadMoreBtn}
          onTouchTap={() => this.handleLoadMoreComments()}
          disabled={this.state.loadMoreBtnDisabled}
        >
          {this.state.loadMoreBtnText}
          <CircularProgress
            className={this.state.loading ? classes.progress : classes.progeessHide}
            color="accent"
            size={30}
          />
        </Button>
        <Dialog open={!! del.comment}>
          <DialogContent>确定要删除此条评论？</DialogContent>
          <DialogActions>
            { del.ing
              ? <Button disabled>取消</Button>
              : <Button onTouchTap={() => this.handlePushClose()}>取消</Button>
            }
            { del.ing
              ? <Button disabled><CircularProgress size={14} /></Button>
              : <Button color="primary" onTouchTap={() => this.handleDelete()}>删除</Button>
            }
          </DialogActions>
        </Dialog>
      </div>
    );
  };

  componentDidMount () {
    this.setState({
      loading: true,
      loadMoreBtnDisabled: true,
      loadMoreBtnText: '已全部加载'
    })

    let loadMoreBtnText, loadMoreBtnDisabled;

    request.get(
      createRequestURI('comments'), {
        validateStatus: status => status === 200
      }
    )
    .then(({ data }) => {
      if (!data.nextPage) {
        loadMoreBtnDisabled = true;
        loadMoreBtnText = '已全部加载';
      } else {
        loadMoreBtnDisabled = false;
        loadMoreBtnText = '加载更多';
      }
      
      this.setState({
        comments: data.comments,
        loading: false,
        nextPage: data.nextPage,
        loadMoreBtnDisabled,
        loadMoreBtnText
      });
    })
  };
}

export default withStyles(styles)(Comment);