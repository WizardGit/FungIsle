#include <iostream>
#include <fstream>
#include <sstream>
#include <string>
#include <cstring>
#include <climits>
#include <queue>

/*
Author: Kaiser Slocum, Geeks for Geeks
Date Finished: 3/2/2022
HOW TO USE:
simply build and run this .cpp file
on a console, you will be asked to input the filename, so, for instance, you would input (without the quotations) "inSample2.txt"
and then the program will run.

In this file are three implementations:
Maximum Bipartite Matching
Ford-Fulkerson
Greedy
NOTE: Because of the way that the algorithms are partitioned using the #define variables,
please do no use the algorithms on files containing more than 50 boxes.  If you do, you MUST
change
increase V to ((the number of boxes) * 2) + 2 {V is used by the Ford-Fulkerson)
M and N variables to (the number of boxes) (M and N is used by the maximum bipartite matching)
(Greedy doesn't use any global define variable)

Big thanks to Geek for Geeks for alot of the Ford-Fulkerson code.
I used these two webpages:
https://www.geeksforgeeks.org/maximum-bipartite-matching/
https://www.geeksforgeeks.org/ford-fulkerson-algorithm-for-maximum-flow-problem/
I pretty much copied the code from the above pages with some slight modifications.

Everything else was written from scratch by me.
NOTE: I also spent some time trying to come up with a scenario that the greedy method does not work.
Although this happens rarely, I did come up with a sequence of boxes and compiled it into a text file trick4.txt
I will attempt to submit that along with the project so you can see why the greedy fails while the others succeed
*/


using namespace std;


int main(int argc, char* argv[])
{
    string line;

    getline(cin, line);
    int numOfBoxes = stoi(line);
    cout << numOfBoxes << endl;
       
    return 0;
}
