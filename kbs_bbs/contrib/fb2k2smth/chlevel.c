#include<stdio.h>
#include"bbs.h"
int main(int*argc,int**argv)
{
		if(argc<4){
				printf("usage:程序名 <文件> <版名> <标题>\n",argv[0]);
				exit(0);
		}
//eg:./postfile /home/bbs/newer/test test 测试一下
	//	printf("%s",argv[0]);
		  chdir(BBSHOME);
	resolve_boards();
    post_file(NULL, "", argv[1], argv[2], argv[3], 0,1);
	exit(0);
}
										 
										 
