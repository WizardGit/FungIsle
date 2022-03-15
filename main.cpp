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

// Number of vertices in given graph
#define V 102
#define M 100
#define N 100

bool bpm(bool bpGraph[M][N], int m, int n, int u, bool seen[], int matchR[])
{
    // Try every job one by one
    for (int v = 0; v < n; v++)
    {
        // If applicant u is interested in
        // job v and v is not visited
        if (bpGraph[u][v] && !seen[v])
        {
            // Mark v as visited
            seen[v] = true;

            // If job 'v' is not assigned to an
            // applicant OR previously assigned
            // applicant for job v (which is matchR[v])
            // has an alternate job available.
            // Since v is marked as visited in
            // the above line, matchR[v] in the following
            // recursive call will not get job 'v' again
            if (matchR[v] < 0 || bpm(bpGraph, m, n, matchR[v], seen, matchR))
            {
                matchR[v] = u;
                return true;
            }
        }
    }
    return false;
}

// Returns maximum number
// of matching from M to N
int maxBPM(bool bpGraph[M][N], int m, int n)
{
    // An array to keep track of the
    // applicants assigned to jobs.
    // The value of matchR[i] is the
    // applicant number assigned to job i,
    // the value -1 indicates nobody is
    // assigned.
    int* matchR = new int[n];
    for (int j = 0; j < n; j++)
    {
        matchR[j] = -1;
    }

    // Count of jobs assigned to applicants
    int result = 0;
    for (int u = 0; u < n; u++)
    {
        // Mark all jobs as not seen
        // for next applicant.
        bool* seen = new bool[n];
        for (int j = 0; j < n; j++)
        {
            seen[j] = 0;
        }

        // Find if the applicant 'u' can get a job
        if (bpm(bpGraph, m, n, u, seen, matchR))
            result++;
    }
    return result;
}

/* Returns true if there is a path from source 's' to sink
't' in residual graph. Also fills parent[] to store the
path */
bool bfs(int rGraph[V][V], int allnodes, int s, int t, int parent[])
{
    // Create a visited array and mark all vertices as not
    // visited
    bool visited[V];
    memset(visited, 0, sizeof(visited));

    // Create a queue, enqueue source vertex and mark source
    // vertex as visited
    queue<int> q;
    q.push(s);
    visited[s] = true;
    parent[s] = -1;

    // Standard BFS Loop
    while (!q.empty()) {
        int u = q.front();
        q.pop();

        for (int v = 0; v < allnodes; v++) {
            if (visited[v] == false && rGraph[u][v] > 0) {
                // If we find a connection to the sink node,
                // then there is no point in BFS anymore We
                // just have to set its parent and can return
                // true
                if (v == t) {
                    parent[v] = u;
                    return true;
                }
                q.push(v);
                parent[v] = u;
                visited[v] = true;
            }
        }
    }

    // We didn't reach sink in BFS starting from source, so
    // return false
    return false;
}

// Returns the maximum flow from s to t in the given graph
int fordFulkerson(int graph[V][V], int allnodes, int s, int t)
{
    int u, v;

    // Create a residual graph and fill the residual graph
    // with given capacities in the original graph as
    // residual capacities in residual graph
    int rGraph[V][V] = { 0 }; // Residual graph where rGraph[i][j]
                // indicates residual capacity of edge
                // from i to j (if there is an edge. If
                // rGraph[i][j] is 0, then there is not)
    for (u = 0; u < V; u++)
        for (v = 0; v < V; v++)
            rGraph[u][v] = graph[u][v];

    int parent[V]; // This array is filled by BFS and to
                // store path

    int max_flow = 0; // There is no flow initially

    // Augment the flow while there is path from source to
    // sink
    while (bfs(rGraph, allnodes, s, t, parent)) {
        // Find minimum residual capacity of the edges along
        // the path filled by BFS. Or we can say find the
        // maximum flow through the path found.
        int path_flow = INT_MAX;
        for (v = t; v != s; v = parent[v]) {
            u = parent[v];
            path_flow = min(path_flow, rGraph[u][v]);
        }

        // update residual capacities of the edges and
        // reverse edges along the path
        for (v = t; v != s; v = parent[v]) {
            u = parent[v];
            rGraph[u][v] -= path_flow;
            rGraph[v][u] += path_flow;
        }

        // Add path flow to overall flow
        max_flow += path_flow;
    }

    // Return the overall flow
    return max_flow;
}

struct Box
{
    int length = 0;
    int width = 0;
    int height = 0;
    bool used = false;

    bool check(Box a, int arr[3])
    {
        if ((a.length > arr[0]) && (a.width > arr[1]) && (a.height > arr[2]))
            return true;
        return false;
    }

    bool canFitIn(Box a)
    {
        int arr[3] = { length, width, height };
        for (int i = 0; i < 3; i++)
        {
            if (check(a, arr) == true)
                return true;
            else
            {
                swap(arr[1], arr[2]);
                if (check(a, arr) == true)
                    return true;
            }
            swap(arr[0], arr[1]);
        }
        return false;
    }

    int getVolume()
    {
        return length * width * height;
    }

    void print()
    {
        cout << "l:" << length << " w:" << width << " h:" << height << endl;
    }
};

// Returns the index of the biggest available box; -1 if there is no available box
int getBiggestAvailableBox(Box boxes[], int numOfBoxes)
{
    int index = -1;
    int i = -1;
    // Get the first unused box
    do
    {
        if (++i < numOfBoxes)
            index++;
        else
            return -1;
    } while (boxes[i].used == true);
    //Update our biggest unused box until go through all the available boxes
    for (i = 0; i < numOfBoxes; i++)
    {
        if ((boxes[i].used == false) && (boxes[i].getVolume() > boxes[index].getVolume()))
            index = i;
    }
    return index;
}
// Returns the index of a box that fits into the specified box; -1 if there is no box that can do so
int getBoxThatFitsIn(Box boxes[], int numOfBoxes, int indexOfABox)
{
    for (int i = 0; i < numOfBoxes; i++)
    {
        if ((boxes[i].used == false) && (boxes[i].getVolume() < boxes[indexOfABox].getVolume()) && (boxes[i].canFitIn(boxes[indexOfABox]) == true))
            return i;
    }
    //cout << "No available box fits into our current box!" << endl;
    return -1;
}
// Returns the index of the biggest available box; -1 if there is no available box
int getBiggestAvailableBoxToFitIn(Box boxes[], int numOfBoxes, int indexOfABox)
{
    //cout << "Can we fit something into box: "; boxes[indexOfABox].print();

    int index = getBoxThatFitsIn(boxes, numOfBoxes, indexOfABox);
    if (index == -1)
        return -1;

    for (int i = 0; i < numOfBoxes; i++)
    {
        if ((boxes[i].used == false) && (boxes[i].getVolume() > boxes[index].getVolume()) && (boxes[i].canFitIn(boxes[indexOfABox]) == true))
            index = i;
    }
    //cout << "Biggest Available box to fit in is:"; boxes[index].print();
    return index;
}

int greedyAlgorithm(Box boxes[], int numOfBoxes)
{
    int total = 0;

    for (int zz = 0; zz < numOfBoxes; zz++)
    {
        int indexOfABox = getBiggestAvailableBox(boxes, numOfBoxes);
        if (indexOfABox == -1)
            return total;
        boxes[indexOfABox].used = true;
        total++;
        //cout << "Biggest box is: "; boxes[indexOfABox].print();

        //Fit in all possible boxes
        int temp = getBiggestAvailableBoxToFitIn(boxes, numOfBoxes, indexOfABox);
        while (temp != -1)
        {
            boxes[temp].used = true;
            indexOfABox = temp;
            //cout << "We just fit in: "; boxes[indexOfABox].print();
            temp = getBiggestAvailableBoxToFitIn(boxes, numOfBoxes, indexOfABox);
        }
    }

    return total;
}
int main(int argc, char** argv)
{
    if (argc <= 1)
    {
        cout << "you have not specified a file.  Don't use the '<' character!" << endl;
        return 0;
    }
    else
        cout << "You specified the file: " << argv[1] << endl;
    string filename;
    filename = argv[1];
    // Parse the File and read all the input into an array of struct boxes
    string line;

    ifstream InputFile(filename);

    if (InputFile.is_open() == false)
    {
        cout << "Cannot open the file named: " << filename << endl;
        return -1;
    }

    getline(InputFile, line);
    stringstream ss(line);
    int numOfBoxes = 0;
    ss >> numOfBoxes;

    cout << "Number of boxes: " << numOfBoxes << endl;
    Box* boxes = new Box[numOfBoxes];
    cout << "Our boxes: " << endl;

    for (int i = 0; i < numOfBoxes; i++)
    {
        getline(InputFile, line);

        stringstream x(line);
        string t;
        getline(x, t, ' ');
        boxes[i].length = stoi(t);
        getline(x, t, ' ');
        boxes[i].width = stoi(t);
        getline(x, t, ' ');
        boxes[i].height = stoi(t);

        boxes[i].print();
    }
    InputFile.close();

    //Call our greedy algorithm
    int total = greedyAlgorithm(boxes, numOfBoxes);
    cout << "Greedy Algorithm says that the smallest number of visible boxes is " << total << endl;

    int allnodes = (numOfBoxes * 2) + 2;
    // Let us create a graph shown in the above example
    int graph[V][V] = { 0 };
    for (int i = 0; i < V; i++)
    {
        for (int j = 0; j < V; j++)
        {
            graph[i][j] = 0;
        }
    }

    for (int i = 1; i < (allnodes / 2); i++)
    {
        graph[0][i] = 1;
    }
    for (int i = (allnodes / 2); i < (allnodes - 1); i++)
    {
        graph[i][allnodes - 1] = 1;
    }
    for (int i = 1; i < (allnodes / 2); i++)
    {
        for (int j = 1; j < (allnodes / 2); j++)
        {
            if (boxes[i - 1].canFitIn(boxes[j - 1]) == true)
                graph[i][j + numOfBoxes] = 1;
            else
                graph[i][j + numOfBoxes] = 0;
        }
    }

    int graph2[V][V] = { { 0, 16, 13, 0, 0, 0 }, { 0, 0, 10, 12, 0, 0 },
            { 0, 4, 0, 0, 14, 0 },  { 0, 0, 9, 0, 0, 20 },
            { 0, 0, 0, 7, 0, 4 },   { 0, 0, 0, 0, 0, 0 } };
    cout << "Ford-Fulkerson Algorithm says that the smallest number of visible boxes is  " << numOfBoxes - fordFulkerson(graph, allnodes, 0, allnodes - 1) << endl;
    //cout << "The maximum possible flow is "<< fordFulkerson(graph2, 6, 0, 5) << endl;

    bool bpGraph2[M][N] = { {0, 1, 1, 0, 0, 0},
                          {1, 0, 0, 1, 0, 0},
                          {0, 0, 1, 0, 0, 0},
                          {0, 0, 1, 1, 0, 0},
                          {0, 0, 0, 0, 0, 0},
                          {0, 0, 0, 0, 0, 1} };

    bool bpGraph[M][N] = { 0 };

    for (int i = 0; i < numOfBoxes; i++)
    {
        for (int j = 0; j < numOfBoxes; j++)
        {
            if (boxes[i].canFitIn(boxes[j]) == true)
                bpGraph[i][j] = 1;
            else
                bpGraph[i][j] = 0;
        }
    }

    cout << "Maximum Bipartite Matching says that the smallest number of visible boxes is " << numOfBoxes - maxBPM(bpGraph, numOfBoxes, numOfBoxes) << endl;
    //cout << "Maximum number of applicants that can get job is " << maxBPM(bpGraph, 6,6) << endl;
    return 0;
}
