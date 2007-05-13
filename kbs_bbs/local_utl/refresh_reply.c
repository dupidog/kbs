
/* count numbers of replys for every topics */

#include "bbs.h"

static int update_reply_count(int fd, fileheader_t* base, int ent, int total, bool match, void* arg) {
    if(match) {
        struct fileheader *fh;
        fh = &base[ent - 1];
        fh->replycount = *(int *)arg;
    }
    return 0;
}

int refresh_board(char* bname) {
    int fd, fd2, origincount, acount, i, j, replycount;
    char dirpath[256], originpath[256];
    char *head, *ahead;
    struct fileheader *ptr, *aptr, *topic;
    struct stat buf, abuf;

    setbdir(DIR_MODE_NORMAL, dirpath, bname);
    setbdir(DIR_MODE_ORIGIN, originpath, bname);
    
    fd = open(originpath, O_RDWR, 0644);
    if(fd == -1) {
        printf("cannot open %s.\n", originpath);
        return 0;
    }
    fd2 = open(dirpath, O_RDWR, 0644);
    if(fd2 == -1) {
        close(fd);
        printf("cannot open %s.\n", dirpath);
        return 0;
    }

    BBS_TRY {
        if(!safe_mmapfile_handle(fd, PROT_READ | PROT_WRITE, MAP_SHARED, &head, &buf.st_size)) {
            printf("cannot mmap %s.\n", originpath);
            close(fd);
            return 0;
        }
        if(!safe_mmapfile_handle(fd2, PROT_READ | PROT_WRITE, MAP_SHARED, &ahead, &abuf.st_size)) {
            printf("cannot mmap %s.\n", dirpath);
            close(fd);
            close(fd2);
            return 0;
        }
        origincount = buf.st_size / sizeof(struct fileheader);
        acount = abuf.st_size / sizeof(struct fileheader);
        ptr = (struct fileheader *)head;
        for(i=0; i<origincount; i++) {
            replycount = 0;
            topic = NULL;
            aptr = (struct fileheader *)ahead;
            for(j=0; j<acount; j++) {
                if(aptr->groupid == ptr->id) {
                    replycount++;
                    if(aptr->groupid == aptr->id)
                        topic = aptr;
                }
                aptr++;
            }
            ptr->replycount = replycount;
            if(topic)
                topic->replycount = replycount;
            ptr++;
        }
    }
    BBS_CATCH {
    }
    BBS_END;
    
    end_mmapfile((void *)head, buf.st_size, -1);
    end_mmapfile((void *)ahead, abuf.st_size, -1);
    close(fd);
    close(fd2);

    printf("board %s is done.\n", bname);

    return 0;
}

static int do_board(struct boardheader* bh, void* arg) {
    refresh_board(bh->filename);
    return 0;
}

int main(int argc, char** argv) {
    int doall;
    
    doall = 0;
    if(argc == 1) {
        doall = 1;
    }
    else if(argc == 2) {
        if(strcmp(argv[1], "--help") == 0) {
            printf("usage:   %s :  refresh reply count for all boards.\n", argv[0]);
            printf("         %s boardname :  refresh one board.\n", argv[0]);
            return 0;
        }
    }
    
    chdir(BBSHOME);
    resolve_boards();
    
    if(doall == 0) {
        refresh_board(argv[1]);
    }
    else {
        apply_boards(do_board, NULL);
    }

    return 0;
}
